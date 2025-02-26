import { visitPath } from '../__utils__/visitPath';
import { screen } from '@testing-library/react';

describe('visit incorrect page', () => {
  it('renders errorPage', () => {
    visitPath('/404');

    expect(screen.getByText('Oops!')).toBeInTheDocument();
    expect(
      screen.getByText('Sorry, an unexpected error has occurred.'),
    ).toBeInTheDocument();
    expect(screen.getByText('Not Found')).toBeInTheDocument();
  });
});
