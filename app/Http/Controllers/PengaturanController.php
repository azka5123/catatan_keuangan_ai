<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\UserFinaces;
use Exception;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('pages.setting.index');
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|nullable',
                'email' => 'sometimes|email|nullable',
                'no_hp' => 'required|numeric|digits_between:10,15',
                'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
                'phone_verified' => 'boolean',
            ]);

            $user = User::find($id);
            if (!$user) {
                return back()->with('error', 'User tidak ditemukan.');
            }

            $updateData = $validated;

            if (isset($validated['phone_verified'])) {
                if ($validated['phone_verified']) {
                    if ($user->no_hp !== $validated['no_hp']) {
                        UserFinaces::where('no_hp', $user->no_hp)->update(['no_hp' => $validated['no_hp']]);
                    }
                } else {
                    unset($updateData['no_hp']);
                }
                unset($updateData['phone_verified']);
            }

            if ($request->hasFile('photo')) {
                if ($user->photo) {
                    Helper::deleteImage($user->photo, 'profil/');
                }
                $photo = Helper::saveImage($request->file('photo'), uniqid(), 'profil/');
                $updateData['photo'] = $photo;
            }

            $user->update($updateData);

            return back()->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
