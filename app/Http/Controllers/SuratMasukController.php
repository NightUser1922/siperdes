            unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
        }

        $nomorSurat = $suratMasuk->nomor_surat;
        $suratMasuk->delete();
        AuditLog::catat($request, 'Hapus data', 'Surat Masuk', 'Menghapus surat masuk ' . $nomorSurat);

        return redirect('/surat-masuk')->with('success', 'Data Surat Masuk berhasil dihapus!');
    }
}
