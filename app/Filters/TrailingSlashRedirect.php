<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class TrailingSlashRedirect implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri();

        // Cek jika bukan file statis dan tidak punya slash di akhir
        if (!is_file(FCPATH . $uri->getPath()) && substr($uri->getPath(), -1) !== '/') {
            $newPath = $uri->getPath() . '/';
            $query = $uri->getQuery() ? '?' . $uri->getQuery() : '';

            return redirect()->to(base_url($newPath . $query));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu apa-apa di sini
    }
}
