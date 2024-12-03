describe('Klik Logout dari Navbar', () => {
    it('Mengklik Logout di Dropdown', () => {
        // Kunjungi halaman awal
        cy.visit('https://localhost/karir/login.php');
        cy.contains('Login');
        cy.get('input[name="username"]').type('hr');
        cy.get('input[name="password"]').type('hr');
        cy.get('button[type="submit"]').click();
        cy.contains('Dashboard');
        cy.get('.dropdown-toggle').click();
  
        // Klik link Logout
        cy.contains('a', 'Logout').click();
    
        // Verifikasi bahwa halaman mengarah ke halaman logout (misalnya, halaman login)
        cy.url().should('include', 'login.php');
    });
  });
  