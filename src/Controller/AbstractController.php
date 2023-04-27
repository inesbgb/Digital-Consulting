<?php

namespace App\Controller;

use App\Model\UserManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    protected Environment $twig;
    protected array|false $user;

    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());
        $userManager = new UserManager();
        session_start();
        $this->user = isset($_SESSION['user_id']) ? $userManager->selectOneById($_SESSION['user_id']) : false;
        $this->twig->addGlobal('user', $this->user);
    }

    protected function verifyRole($role): bool
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        } else {
            $userManager = new UserManager();
            $user = $userManager->selectOneById($_SESSION['user_id']);
            if ($user['role'] !== $role) {
                return false;
            }
        }
        return true;
    }

    protected function isConnect(): bool
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }
}