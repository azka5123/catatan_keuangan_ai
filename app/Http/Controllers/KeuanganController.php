<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\UserFinaces;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'keterangan' => 'required|string',
            'jenis' => 'required|string',
            'nominal' => 'required|numeric',
        ]);
    }
    public function index()
    {
        $startOfMonth =  Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return $this->getUserFinaces($startOfMonth, $endOfMonth);
    }

    public function search(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'jenis'      => 'nullable|in:pemasukan,pengeluaran,semua',
        ]);
        return $this->getUserFinaces($request->start_date, $request->end_date, $request->jenis);
    }

    private function getUserFinaces($starDate, $endDate, $jenis = null)
    {
        $noHp = Auth::user()->no_hp;
        $query = UserFinaces::where('no_hp', $noHp)
            ->whereBetween('tanggal', [$starDate, $endDate]);

        if ($jenis && $jenis !== 'semua') {
            $query->where('jenis', $jenis);
        }

        $dataUser = $query->orderBy('created_at', 'desc')->paginate(20);

        $summary = UserFinaces::selectRaw("
            SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as income,
            SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as outcome
        ")
            ->where('no_hp', $noHp)
            ->whereBetween('tanggal', [$starDate, $endDate])
            ->first();

        $balances = UserFinaces::selectRaw("
            SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as income,
            SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as outcome
        ")
            ->where('no_hp', $noHp)
            ->first();

        $income = $summary->income;
        $outcome = $summary->outcome;
        $balance = $balances->income - $balances->outcome;
        return view('pages.keuangan.index', compact('dataUser', 'income', 'outcome', 'balance'));
    }


    public function store(Request $request)
    {
        try {
            $req = $this->validateRequest($request);
            $req['no_hp'] = Auth::user()->no_hp;
            UserFinaces::create($req);
            return redirect()->route('keuangan.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            return redirect()->route('keuangan.index')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $req = $this->validateRequest($request);
            $req['no_hp'] = Auth::user()->no_hp;
            UserFinaces::updateOrCreate(['id' => $request->id], $req)->update($req);
            return redirect()->route('keuangan.index')->with('success', 'Data berhasil diperbarui');
        } catch (Exception $e) {
            return redirect()->route('keuangan.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        UserFinaces::find($id)->delete();
        return redirect()->route('keuangan.index')->with('success', 'Data berhasil dihapus');
    }
}
