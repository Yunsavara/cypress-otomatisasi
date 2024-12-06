describe('Tambah Lowongan Karir', () => {
    it('Kunjungi Halaman Login, Check Text Login dan Login', () => {
        cy.visit('https://localhost/karir/login.php');
        cy.contains('Login');
        cy.get('input[name="username"]').type('hr');
        cy.get('input[name="password"]').type('hr');
        cy.get('button[type="submit"]').click();
        cy.contains('Dashboard'); 

        cy.contains('Tambah karir').should('be.visible');
        cy.get('a[href="tambah_karir.php"]').click();
        cy.contains('Tambah Lowongan Karir');
        cy.get('input[name="posisi"]').type('Manager');
        cy.get('textarea[name="deskripsi"]').type('Deskripsi Manager');
        const filePath = '../fixtures/Manager.jpg';
        cy.get('input[name="gambar"]').attachFile(filePath);
        cy.get('button[type="submit"]').click();
        cy.visit('https://localhost/karir/karir.php');
    })
})