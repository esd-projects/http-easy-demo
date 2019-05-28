<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/7
 * Time: 10:48
 */

namespace Demo\Controller;

use DI\Annotation\Inject;
use Demo\Model\User;
use Demo\Service\UserService;
use ESD\Go\GoController;
use ESD\Plugins\EasyRoute\Annotation\GetMapping;
use ESD\Plugins\EasyRoute\Annotation\PostMapping;
use ESD\Plugins\EasyRoute\Annotation\RequestBody;
use ESD\Plugins\EasyRoute\Annotation\RequestParam;
use ESD\Plugins\EasyRoute\Annotation\RestController;
use ESD\Plugins\Security\Annotation\PreAuthorize;
use ESD\Plugins\Security\Beans\Principal;

/**
 * @RestController("user")
 * Class CUser
 * @package Demo\Controller
 */
class CUser extends GoController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

    /**
     * @param string|null $controllerName
     * @param string|null $methodName
     * @return mixed|void
     */
    public function initialization(?string $controllerName, ?string $methodName)
    {
        parent::initialization($controllerName, $methodName);
        $this->response->addHeader("Content-type", "text/html;charset=UTF-8");
    }

    /**
     * @GetMapping()
     * @return string
     */
    public function login()
    {
        $principal = new Principal();
        $principal->addRole("user");
        $principal->setUsername("user");
        $this->setPrincipal($principal);
        if ($this->session->isAvailable()) {
            return "已登录" . $this->session->getId() . $this->session->getAttribute("test");
        } else {
            $this->session->refresh();
            $this->session->setAttribute("test", "hello");
            return "登录" . $this->session->getId() . $this->session->getAttribute("test");
        }

    }

    /**
     * @GetMapping()
     * @return string
     */
    public function logout()
    {
        $this->session->invalidate();
        return "注销";
    }

    /**
     * @GetMapping()
     * @RequestParam("id")
     * @PreAuthorize(value="hasRole('user')")
     * @param $id
     * @return User
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Plugins\Mysql\MysqlException
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ReflectionException
     */
    public function user($id)
    {
        return $this->userService->getUser($id);
    }

    /**
     * @PostMapping()
     * @PreAuthorize(value="hasRole('user')")
     * @RequestBody("user")
     * @param User $user
     * @return User|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Plugins\Mysql\MysqlException
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ReflectionException
     */
    public function updateUser(User $user)
    {
        return $this->userService->updateUser($user);
    }
}