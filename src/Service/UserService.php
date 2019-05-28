<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/7
 * Time: 10:50
 */

namespace Demo\Service;

use Demo\Model\User;
use DI\Annotation\Inject;
use ESD\Plugins\Cache\Annotation\Cacheable;
use ESD\Plugins\Cache\Annotation\CacheEvict;
use ESD\Plugins\Mysql\Annotation\Transactional;
use Psr\Log\LoggerInterface;

class UserService
{
    /**
     * @Inject()
     * @var \MysqliDb
     */
    private $db;

    /**
     * @Inject()
     * @var LoggerInterface
     */
    private $log;

    /**
     * get操作创建缓存
     * @Cacheable(key="$p[0]",namespace="user1")
     * @param $id
     * @return User|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Plugins\Mysql\MysqlException
     * @throws \ESD\Plugins\Validate\ValidationException
     * @throws \ReflectionException
     */
    public function getUser($id)
    {
        return User::select($id);
    }

    /**
     * update操作修改缓存
     * @Transactional()
     * @CacheEvict(key="$p[0]->id",namespace="user1")
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
        $user->updateSelective();
        return $this->getUser($user->id);
    }
}