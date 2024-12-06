describe('Redirect ke Medikaprakarsa.co.id', () => {
  it('Kunjungi Halaman Home', () => {
    cy.visit('https://localhost/karir/');
    cy.contains('a', 'Home').click();
    })
}) 