describe('Test Halaman Navbar', () => {
    it('Memeriksa Teks di Navbar', () => {
        cy.visit('https://localhost/karir/'); 
        
        cy.contains('a', 'Home'); 
        cy.contains('a', 'The Clinic'); 
        cy.contains('a', 'Our Services'); 
        cy.contains('a', 'Meet the Team'); 
        cy.contains('a', 'The Subsidiary');
        cy.contains('a', 'Contact'); 
        cy.contains('a', 'Careers');
        cy.contains('Member of NIRWANA ABADI SENTOSA');
        cy.contains('LOGIN');
    });
});
