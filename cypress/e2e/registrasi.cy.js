describe('Test Registrasi', () => {
  it('Kunjungi Halaman Registrasi dan daftar peserta', () => {
    cy.visit('https://localhost/karir/daftar_peserta.php');
    cy.contains('Pendaftaran Peserta');
 
    // Pendaftaran Peserta
    cy.get('input[name="nama_lengkap"]').type('Peserta Testing');
    cy.get('input[name="email"]').type('peserta@testing.com');
    cy.get('textarea[name="alamat"]').type('Jl. Pekerjaan No. 1');
    cy.get('input[name="tanggal_lahir"]').type('1990-01-01');
    cy.get('input[name="lulusan"]').type('Lulusan Peserta');
    cy.get('input[name="pekerjaan_yang_dilamar"]').type('Pekerjaan Peserta');

    // Riwayat Pekerjaan
    cy.get('input[name="nama_perusahaan[]"]').eq(0).type('Perusahaan Pekerjaan 1');
    cy.get('input[name="posisi_pekerjaan[]"]').eq(0).type('Posisi Pekerjaan 1');
    cy.get('input[name="tanggal_mulai[]"]').eq(0).type('2022-01-01');
    cy.get('input[name="tanggal_selesai[]"]').eq(0).type('2022-12-31');

    cy.get('#tambah_riwayat_pekerjaan').click();
    cy.get('input[name="nama_perusahaan[]"]').eq(1).type('Perusahaan Pekerjaan 2');
    cy.get('input[name="posisi_pekerjaan[]"]').eq(1).type('Posisi Pekerjaan 2');
    cy.get('input[name="tanggal_mulai[]"]').eq(1).type('2023-01-01');
    cy.get('input[name="tanggal_selesai[]"]').eq(1).type('2023-12-31');

    // Sertifikat
    cy.get('input[name="nama_sertifikat[]"]').eq(0).type('Sertifikat');
    cy.get('select[name="sertifikat_status[]"]').eq(0).select('nonaktif');
    cy.get('input[name="masa_berlaku[]"]').eq(0).type('2023-01-01'); 

    const filePathSertifikat = '../fixtures/sertifikat.png';
    cy.get('input[name="file_sertifikat[]"]').eq(0).attachFile(filePathSertifikat);


    // CV
    const filePathCV = '../fixtures/cv.png';
    cy.get('input[name="cv"]').attachFile(filePathCV);

    // Tujuan Melamar
    cy.get('textarea[name="tujuan_melamar"]').type('Tujuan Melamar Peserta');
    
    // Bidang Pekerjaan Disenangi
    cy.get('input[name="bidang_disenangi"]').type('Bidang Pekerjaan Disenangi');
    
    // Bidang Pekerjaan Tidak Disenangi
    cy.get('input[name="bidang_tidak_disenangi"]').type('Bidang Pekerjaan Tidak Disenangi');
    
    // Pengetahuan Keahlian
    cy.get('textarea[name="pengetahuan_keahlian"]').type('Pengetahuan Keahlian Peserta');
    
    // Gaji Harapan
    cy.get('input[name="gaji_harapan"]').type('Gaji Harapan Peserta');
    
    // Kapan Bisa Bekerja
    cy.get('input[name="kapan_bisa_bekerja"]').type('Kapan Bisa Bekerja Peserta');

    cy.get('button[type="submit"]').click();
    })
})