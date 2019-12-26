<h1 align="center"> think-rbac </h1>

<p align="center"> thinkphp6的rbac权限控制系统 基于mysql</p>


## 安装

1、首先用composer安装
```shell
$ composer require lihq1403/think-rbac:^1.0
```

2、发布资源
```shell
$ php think lihq1403:rbac-publish
```

会生成一个rbac.php的配置文件在config目录下
```php
    /**
     * 用户表，这是我的示例，要改成你们自己的
     * 用来做关联的，所以一定要有
     */
    'user_model' => \app\common\models\AdminUser::class,

    /**
     * 是否忽略未定义的权限
     */
    'skip_undefined_permission' => true,

    /**
     * 可以跳过权限验证的 模块-控制器-方法
     */
    'continue_list' => [
        'module' => ['admin'],
        'controller' => ['test'],
        'action' => ['test']
    ],
```

3、数据迁移

```shell
$ php think lihq1403:rbac-migrate
```
然后就会发现数据库多了5张表，表字段具体内容可看代码

```$xslt
rbac_role  角色 表
rbac_user_role 用户角色 中间表
rbac_permission_group 权限组 表
rbac_permission 权限规则 表
rbac_role_permission_group 角色权限组 中间表
rbac_log 请求日志 表
```

4、使用

在你需要用到权限的用户model里面，引入`use RbacUser;`
```$xslt
如
namespace app\common\model;

use think\Model;
use Lihq1403\ThinkRbac\traits\RbacUser;

class AdminUser extends Model
{
    /**
     * 超级管理员id，该id的管理员拥有最高权限
     */
    const SUPER_ADMINISTRATOR_ID = 1;

    use RBACUser;
}

```
这样就算是可以开始使用了

## 直接使用
提供了controller，只需要定义路由就好了，比如
```php
// rbac 管理
Route::group('rbac', function () {

    // 角色管理
    Route::post('role', 'Lihq1403\ThinkRbac\controller\RBACController@addRole'); // 添加角色
    Route::put('role', 'Lihq1403\ThinkRbac\controller\RBACController@editRole'); // 修改角色
    Route::delete('role', 'Lihq1403\ThinkRbac\controller\RBACController@delRole'); // 删除角色
    Route::get('roles', 'Lihq1403\ThinkRbac\controller\RBACController@getRoles'); // 角色列表
    Route::get('role/permission-group', 'Lihq1403\ThinkRbac\controller\RBACController@roleHoldPermissionGroup'); // 角色拥有的权限列表
    Route::post('role/change-permission-group', 'Lihq1403\ThinkRbac\controller\RBACController@diffPermissionGroup'); // 角色更换的权限列表

    // 权限组管理
    Route::post('permission_group', 'Lihq1403\ThinkRbac\controller\RBACController@addPermissionGroup'); // 权限组新增
    Route::put('permission_group', 'Lihq1403\ThinkRbac\controller\RBACController@editPermissionGroup'); // 权限组编辑
    Route::delete('permission_group', 'Lihq1403\ThinkRbac\controller\RBACController@delPermissionGroup');// 权限组删除
    Route::get('permission_groups', 'Lihq1403\ThinkRbac\controller\RBACController@getPermissionGroups'); // 权限组列表

    // 权限管理
    Route::post('permission', 'Lihq1403\ThinkRbac\controller\RBACController@addPermission'); // 权限新增
    Route::put('permission', 'Lihq1403\ThinkRbac\controller\RBACController@editPermission'); // 权限编辑
    Route::delete('permission', 'Lihq1403\ThinkRbac\controller\RBACController@delPermission'); // 权限删除
    Route::get('permissions', 'Lihq1403\ThinkRbac\controller\RBACController@getPermissions'); // 权限列表

    // 管理员管理
    Route::post('admin-user/role', 'Lihq1403\ThinkRbac\controller\RBACController@userAssignRoles'); // 给管理员分配角色
    Route::delete('admin-user/role', 'Lihq1403\ThinkRbac\controller\RBACController@userCancelRoles'); // 给管理员删除角色
    Route::post('admin-user/sync-role', 'Lihq1403\ThinkRbac\controller\RBACController@userSyncRoles'); // 同步管理员角色

    // 日志管理
    Route::get('logs', 'Lihq1403\ThinkRbac\controller\RBACController@getLog'); // 获取日志
});
```


## 自定义使用

### 实例化
```php
$rbac = new Rbac();
// 或者使用门面
\Lihq1403\ThinkRbac\facade\RBAC::addPermissionGroup();
```


### 权限组
##### 增加权限组
```php
$rbac->addPermissionGroup('用户管理', 'user_manager', '用户管理权限组');
```
##### 编辑权限组
```php
$rbac->editPermission(1, ['name' => '用户列表111']);
```
##### 删除权限组
```php
$rbac->delPermission(1);
```

### 角色
##### 添加角色
```php
$rbac->addRole('用户管理的管理员', '描述');
```
##### 编辑角色
```php
$rbac->editRole(1, ['name' => '用户管理的管理员']);
```
##### 删除角色
```php
$rbac->delRole(1);
```
##### 禁用角色
```php
$rbac->closeRole(2);
```
##### 获取所有角色，分页显示
```php
$rbac->getRoles($map = [], $field = [], $page = 1, $page_rows = 10);

返回结果是分页的
array(3) {
  ["page"] => int(1)
  ["total"] => int(1)
  ["list"] => array(1) {
    [0] => array(6) {
      ["id"] => int(2)
      ["name"] => string(24) "用户管理的管理员"
      ["description"] => string(2) "11"
      ["create_time"] => string(19) "2019-06-27 14:02:34"
      ["update_time"] => string(19) "2019-06-27 14:02:34"
      ["status"] => int(0)
    }
  }
}
```
##### 获取角色所有权限组，已拥有和未拥有
```php
$rbac->roleHoldPermissionGroup(2);

返回结果
array(3) {
  [0] => array(5) {
    ["id"] => int(4)
    ["name"] => string(12) "用户管理"
    ["description"] => string(21) "用户管理权限组"
    ["code"] => string(12) "user_manager"
    ["hold"] => int(0) // 1是 0否
  }
  [1] => array(5) {
    ["id"] => int(5)
    ["name"] => string(3) "111"
    ["description"] => string(0) ""
    ["code"] => string(2) "11"
    ["hold"] => int(0)
  }
  [2] => array(5) {
    ["id"] => int(6)
    ["name"] => string(12) "用户列表"
    ["description"] => string(0) ""
    ["code"] => string(2) "22"
    ["hold"] => int(1)
  }
}
```

### 角色-权限组
##### 分配权限
```php
$rbac->assignPermissionGroup(2, 'user_manager');
第二个参数不填，则添加所有权限组
```
##### 收回权限
```php
$rbac->cancelPermissionGroup(2,['user_manager']);
第二个参数不填，则收获所有权限组
```
##### 修改角色权限，已有未被选择会被删除，未有已选择会新增
```php
$rbac->diffPermissionGroup(2, [22]);
```

### 用户-角色
##### 先查找用户
```php
$user = AdminUser::get(1);
```

##### 分配角色
```php
$user->assignRoles([1,2,3]);
```

##### 收回角色
```php
$user->cancelRoles([1,2,3]);
```

##### 差值分配角色
```php
$user->diffRoles([1,2,3]);
```

##### 用户所有角色
```php
$user->allRoles();
返回结果
array(1) {
  [0] => array(3) {
    ["role_id"] => int(2)
    ["name"] => string(24) "用户管理的管理员"
    ["description"] => string(2) "11"
  }
}
```

### 权限检查 传入用户id，一般配合中间件来使用
```php
$rbac->can(1);

如果没有权限，会抛出Lihq1403\ThinkRbac\exception\ForbiddenException异常
```
例子
```php
    /**
     * rbac中间件
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ForbiddenException
     */
    public function handle(Request $request, \Closure $next)
    {
        $uid = AdminAuth::uid();

        // 超级管理员不需要进行权限控制
        if ($uid !== AdminUser::SUPER_ADMINISTRATOR_ID) {
            // 检查权限
            try {
                RBAC::can($uid);
            } catch (\Lihq1403\ThinkRbac\exception\ForbiddenException $exception){
                throw new ForbiddenException($exception->getMessage());
            }
        }

        // 记录日志
        RBAC::log($uid);

        return $next($request);
    }
```

### 日志相关
##### 保存日志
```php
$rbac->log(1);

传入用户id记录
```
##### 日志列表获取
```php
$rbac->getLogList(1,10);

结果如下格式：
array(1) {
  ["page"] => int(1)
  ["total"] => int(1)
  ["list"] => array(1) {
    [0] => array(8) {
      ["id"] => int(3)
      ["user_id"] => int(1)
      ["method"] => string(3) "GET"
      ["path"] => string(16) "api/admin/noAuth"
      ["ip"] => string(12) "192.168.56.1"
      ["input"] => object(stdClass)#255 (1) {
        ["hhh"] => string(1) "2"
      }
      ["create_time"] => string(19) "2019-07-10 15:23:12"
      ["user"] => array(2) {
        ["id"] => int(1)
        ["username"] => string(5) "admin"
      }
    }
  }
}
```



## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/lihq1403/think-rbac/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/lihq1403/think-rbac/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT