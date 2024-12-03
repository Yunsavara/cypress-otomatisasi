describe('Test Peluang Karir', () => {
    it('Kunjungi Halaman Peluang Karir', () => {
        cy.visit('https://localhost/karir');
        cy.contains('Peluang Karir').click();
        cy.contains('Peluang Karir');
    });

    it('Daftar Peluang Karir', () => {
        cy.visit('https://localhost/karir');
        cy.contains('Peluang Karir').click();
        cy.contains('Peluang Karir');
        cy.contains('Daftar Sekarang').click();
        cy.contains('Pendaftaran Peserta');
    });
})