<?php
/**
 * @author tmtuan
 * 
 * Trong giai đoạn 1, chỉ phân quyền truy cập cho tài khoản theo nhóm tài khoản
 */

namespace App\Filters;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class GroupPermission implements FilterInterface {
    // Define all restricted paths in one place
    protected $restrictedPaths = [
        '/acp/post',
        '/acp/page',
        '/acp/product',
        '/acp/theme-option',
        '/acp/shop'
    ];
    
    // Define permission rules for different groups
    protected $groupPermissions = [
        1 => ['/acp/post', '/acp/page', '/acp/product', '/acp/theme-option', '/acp/shop'], // Admin - all access
        2 => ['/acp/product', '/acp/shop'], // Product manager
        3 => ['/acp/post', '/acp/page'], // Content manager
    ];
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after controller execution
        return $response;
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (! function_exists('logged_in'))
        {
            helper('auth');
        }

        $authenticate = Services::authentication();

        // if no user is logged in then send to the login form
        if (! $authenticate->check())
        {
            session()->set('redirect_url', current_url());
            return redirect('login');
        }
        $user = user();
        
        // Check if user is root
        if ( $user->is_root ) {
            return;
        }
        
        // Get user's group ID
        $gid = $user->gid;
        
        // Get current URI path
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Check if current path starts with any of the restricted paths
        $restricted = false;
        $hasPermission = false;
        
        foreach ($this->restrictedPaths as $restrictedPath) {
            if (strpos($path, $restrictedPath) === 0) {
                $restricted = true;
                
                // Check if user has permission for this path
                if (isset($this->groupPermissions[$gid])) {
                    foreach ($this->groupPermissions[$gid] as $allowedPath) {
                        if (strpos($path, $allowedPath) === 0) {
                            $hasPermission = true;
                            break;
                        }
                    }
                }
                
                break;
            }
        }
        
        // If path is restricted and user doesn't have permission, redirect to dashboard or show error
        if ($restricted && !$hasPermission) {
            return redirect()->to('/acp/dashboard')->with('error', 'You do not have permission to access this page.');
        }
        
        // Allow access if not restricted or user has permission
        return;
    }
}