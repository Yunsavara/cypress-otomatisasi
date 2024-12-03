describe('Test Warna dan Teks Button Login', () => {
    it('Halaman Homepage', () => {
        cy.visit('https://localhost/karir/login.php');
        cy.contains('button', 'Login')
        .should('have.css', 'background-color', 'rgb(0, 123, 255)');
    });
});