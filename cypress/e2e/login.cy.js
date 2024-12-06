describe('Test Login Valid', () => {
  // it('Kunjungi Halaman Login, Check Text Login dan Login', () => {
  //   cy.visit('https://localhost/karir/login.php');
  //   cy.contains('Login');
  //   cy.get('input[name="username"]').type('hr');
  //   cy.get('input[name="password"]').type('hr');
  //   cy.get('button[type="submit"]').click();
  //   cy.contains('Dashboard');
  // })

  it('Kunjungi Halaman Login, Check Text Login dan Login Invalid', () => {
    cy.visit('https://localhost/karir/login.php');
    cy.contains('Login');
    cy.get('input[name="username"]').type('hr');
    cy.get('input[name="password"]').type('hr2');
    cy.get('button[type="submit"]').click();
    cy.contains('Password HR salah.');
  })
})