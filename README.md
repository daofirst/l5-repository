# Laravel 5 Repositories

Laravel 5 Repositories is used to abstract the data layer, making our application more flexible to maintain.

laravel 5库用于laravel抽象数据层，使我们的应用程序的维护更加灵活。

[![Latest Stable Version](https://poser.pugx.org/prettus/l5-repository/v/stable)](https://packagist.org/packages/prettus/l5-repository) [![Total Downloads](https://poser.pugx.org/prettus/l5-repository/downloads)](https://packagist.org/packages/prettus/l5-repository) [![Latest Unstable Version](https://poser.pugx.org/prettus/l5-repository/v/unstable)](https://packagist.org/packages/prettus/l5-repository) [![License](https://poser.pugx.org/prettus/l5-repository/license)](https://packagist.org/packages/prettus/l5-repository)
[![Analytics](https://ga-beacon.appspot.com/UA-61050740-1/l5-repository/readme)](https://packagist.org/packages/prettus/l5-repository)
[![Code Climate](https://codeclimate.com/github/andersao/l5-repository/badges/gpa.svg)](https://codeclimate.com/github/andersao/l5-repository)

#### See versions: [1.0.*](https://github.com/andersao/l5-repository/tree/1.0.4) / [2.0.*](https://github.com/andersao/l5-repository/tree/2.0.14)
#### Migrate to: [2.0](migration-to-2.0.md) / [2.1](migration-to-2.1.md)

You want to know a little more about the Repository pattern? [Read this great article](http://bit.ly/1IdmRNS).

## Table of Contents

- <a href="#installation">Installation</a>
    - <a href="#composer">Composer</a>
    - <a href="#laravel">Laravel</a>
- <a href="#methods">Methods</a>
    - <a href="#prettusrepositorycontractsrepositoryinterface">RepositoryInterface</a>
    - <a href="#prettusrepositorycontractsrepositorycriteriainterface">RepositoryCriteriaInterface</a>
    - <a href="#prettusrepositorycontractscacheableinterface">CacheableInterface</a>
    - <a href="#prettusrepositorycontractspresenterinterface">PresenterInterface</a>
    - <a href="#prettusrepositorycontractscriteriainterface">CriteriaInterface</a>
- <a href="#usage">Usage</a>
	- <a href="#create-a-model">Create a Model</a>
	- <a href="#create-a-repository">Create a Repository</a>
	- <a href="#generators">Generators</a>
	- <a href="#use-methods">Use methods</a>
	- <a href="#create-a-criteria">Create a Criteria</a>
	- <a href="#using-the-criteria-in-a-controller">Using the Criteria in a Controller</a>
	- <a href="#using-the-requestcriteria">Using the RequestCriteria</a>
- <a href="#cache">Cache</a>
    - <a href="#cache-usage">Usage</a>
    - <a href="#cache-config">Config</a>
- <a href="#validators">Validators</a>
    - <a href="#using-a-validator-class">Using a Validator Class</a>
        - <a href="#create-a-validator">Create a Validator</a>
        - <a href="#enabling-validator-in-your-repository-1">Enabling Validator in your Repository</a>
    - <a href="#defining-rules-in-the-repository">Defining rules in the repository</a>
- <a href="#presenters">Presenters</a>
    - <a href="#fractal-presenter">Fractal Presenter</a>
        - <a href="#create-a-presenter">Create a Fractal Presenter</a>
        - <a href="#implement-interface">Model Transformable</a>
    - <a href="#enabling-in-your-repository-1">Enabling in your Repository</a>

## Installation

### Composer

Execute the following command to get the latest version of the package:

```
composer require prettus/l5-repository
```

### Laravel

In your `config/app.php` add `Prettus\Repository\Providers\RepositoryServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Prettus\Repository\Providers\RepositoryServiceProvider::class,
],
```

If Lumen

```php
$app->register(Prettus\Repository\Providers\LumenRepositoryServiceProvider::class);
```

Publish Configuration

```shell
php artisan vendor:publish
```

## Methods

### Prettus\Repository\Contracts\RepositoryInterface

- all($columns = array('*'))
- first($columns = array('*'))
- paginate($limit = null, $columns = ['*'])
- find($id, $columns = ['*'])
- findByField($field, $value, $columns = ['*'])
- findWhere(array $where, $columns = ['*'])
- findWhereIn($field, array $where, $columns = [*])
- findWhereNotIn($field, array $where, $columns = [*])
- create(array $attributes)
- update(array $attributes, $id)
- updateOrCreate(array $attributes, array $values = [])
- delete($id)
- orderBy($column, $direction = 'asc');
- with(array $relations);
- has(string $relation);
- whereHas(string $relation, closure $closure);
- hidden(array $fields);
- visible(array $fields);
- scopeQuery(Closure $scope);
- getFieldsSearchable();
- setPresenter($presenter);
- skipPresenter($status = true);


### Prettus\Repository\Contracts\RepositoryCriteriaInterface

- pushCriteria($criteria)
- popCriteria($criteria)
- getCriteria()
- getByCriteria(CriteriaInterface $criteria)
- skipCriteria($status = true)
- getFieldsSearchable()

### Prettus\Repository\Contracts\CacheableInterface

- setCacheRepository(CacheRepository $repository)
- getCacheRepository()
- getCacheKey($method, $args = null)
- getCacheMinutes()
- skipCache($status = true)

### Prettus\Repository\Contracts\PresenterInterface

- present($data);

### Prettus\Repository\Contracts\Presentable

- setPresenter(PresenterInterface $presenter);
- presenter();

### Prettus\Repository\Contracts\CriteriaInterface

- apply($model, RepositoryInterface $repository);

### Prettus\Repository\Contracts\Transformable

- transform();


## Usage

### 创建一个模型

通常创建模型，都会设置可批量赋值的字段。

```php namespace App;

class Post extends Eloquent { // or Ardent, Or any other Model Class

    protected $fillable = [
        'title',
        'author',
        ...
     ];

     ...
}
```

### 创建一个仓库

```php namespace App;

use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Post";
    }
}
```

### Generators--创建器

通过创建器轻松创建的你仓库

#### Config

你首先必须配置你的仓库文件的储存位置。默认情况下默认文件夹是‘app’，默认的命名空间是‘App’。
请注意：paths数组的值既作为文件路径，又作为命名空间路径。不过是放松的，不区分‘/’（正斜杠）和‘\’（反斜杠）

```php
    ...
    'generator'=>[
        'basePath'=>app_path(),
        'rootNamespace'=>'App\\',
        'paths'=>[
            'models'       => 'Entities',
            'repositories' => 'Repositories',
            'interfaces'   => 'Repositories',
            'transformers' => 'Transformers',
            'presenters'   => 'Presenters',
            'validators'   => 'Validators',
            'controllers'  => 'Http/Controllers',
            'provider'     => 'RepositoryServiceProvider',
            'criteria'     => 'Criteria',
        ]
    ]
```

您可能希望将项目文件夹的根保存到app下，并添加另一个命名空间，例如

```php
    ...
     'generator'=>[
        'basePath'      => base_path('src/Lorem'),
        'rootNamespace' => 'Lorem\\'
    ]
```

另外：你可能希望自定义生成的类最终被保存的地方，可以自定义编辑路径完成。例如：

```php
    'generator'=>[
        'basePath'=>app_path(),
        'rootNamespace'=>'App\\',
        'paths'=>[
            'models'=>'Models',
            'repositories'=>'Repositories\\Eloquent',
            'interfaces'=>'Contracts\\Repositories',
            'transformers'=>'Transformers',
            'presenters'=>'Presenters'
            'validators'   => 'Validators',
            'controllers'  => 'Http/Controllers',
            'provider'     => 'RepositoryServiceProvider',
            'criteria'     => 'Criteria',
        ]
    ]
```

#### Commands--命令

生成你需要的一切模型，运行一下命令：

```terminal
php artisan make:entity Post
```

这个命令将创建控制器（controller）、验证器（Validator）、模型（Model）、仓库（Repository）、呈现器（Presenter）和转换器（Transform）的类文件。它还将创建一个新的服务提供商，将用于绑定具有相应存储库接口的功能的存储库，加载它，添加到你的AppServiceProvider@register中：

```php
    $this->app->register(RepositoryServiceProvider::class);
```

您也可以通过存储库命令的选项，因为这个命令是一个包装器；你可以单独为你的模型生成一个仓库，使用下面的命令

```terminal
php artisan make:repository Post
```

为你的文章添加Blog命名空间，使用一下命令

```terminal
php artisan make:repository "Blog\Post"
```

创建时设置可填充字段

```terminal
php artisan make:repository "Blog\Post" --fillable="title,content"
```

创建时设置可填充字段，迁移文件及验证规则：

```terminal
php artisan make:entity Cat --fillable="title:string,content:text" --rules="title=>required|min:2, content=>sometimes|min:10"
```

在你的route.php文件中创建一个资源路由（含增删改查）

 ```php
 Route::resource('cats', CatsController::class);
 ```

在运行命令时，你可以设置默认的Entities文件夹和Repositories里的文件夹。

这样做，只是现在你做的是为您的真正的存储库绑定它的接口，例如在您自己的存储库服务提供商。

```php
App::bind('{YOUR_NAMESPACE}Repositories\PostRepository', '{YOUR_NAMESPACE}Repositories\PostRepositoryEloquent');
```

使用：

```php
public function __construct({YOUR_NAMESPACE}Repositories\PostRepository $repository){
    $this->repository = $repository;
}
```

或者你可以使用artisan命令进行绑定

```php
php artisan make:bindings Cats
```

### Use methods--使用方法

```php
namespace App\Http\Controllers;

use App\PostRepository;

class PostsController extends BaseController {

    /**
     * @var PostRepository
     */
    protected $repository;

    public function __construct(PostRepository $repository){
        $this->repository = $repository;
    }

    ....
}
```

从仓库中获取全部数据

```php
$posts = $this->repository->all();
```

从仓库中获取分页数据

```php
$posts = $this->repository->paginate($limit = null, $columns = ['*']);
```

通过id获取结果集

```php
$post = $this->repository->find($id);
```

模型隐藏属性

```php
$post = $this->repository->hidden(['country_id'])->find($id);
```

仅显示模型的特定属性

```php
$post = $this->repository->visible(['id', 'state_id'])->find($id);
```

加载模型的关联关系

```php
$post = $this->repository->with(['state'])->find($id);
```

通过字段值匹配获取结果集

```php
$posts = $this->repository->findByField('country_id','15');
```

根据多个字段值匹配获取结果集

```php
$posts = $this->repository->findWhere([
    //Default Condition =
    'state_id'=>'10',
    'country_id'=>'15',
    //Custom Condition
    ['columnName','>','10']
]);
```

根据字段值是否存在与多个value值中获取结果集（in--子查询）

```php
$posts = $this->repository->findWhereIn('id', [1,2,3,4,5]);
```

根据字段值是否不存在与多个value值中获取结果集（NotIn--子查询）

```php
$posts = $this->repository->findWhereNotIn('id', [6,7,8,9,10]);
```

使用自定义查询范围获取全部数据



```php
$posts = $this->repository->scopeQuery(function($query){
    return $query->orderBy('sort_order','asc');
})->all();
```

在仓库中创建新的记录

```php
$post = $this->repository->create( Input::all() );
```

在仓库中更新记录

```php
$post = $this->repository->update( Input::all(), $id );
```

在仓库中删除记录

```php
$this->repository->delete($id)
```

### Create a Criteria--创建一个标准（条件）

#### Using the command--使用命令

```terminal
php artisan make:criteria My
```

标准是一种通过根据你需要的应用特定条件来改变查询库的方法，你可以在你的仓库中添加多个标准

```php

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class MyCriteria implements CriteriaInterface {

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('user_id','=', Auth::user()->id );
        return $model;
    }
}
```

### Using the Criteria in a Controller --在你的控制器中使用标准

```php

namespace App\Http\Controllers;

use App\PostRepository;

class PostsController extends BaseController {

    /**
     * @var PostRepository
     */
    protected $repository;

    public function __construct(PostRepository $repository){
        $this->repository = $repository;
    }


    public function index()
    {
        $this->repository->pushCriteria(new MyCriteria1());
        $this->repository->pushCriteria(MyCriteria2::class);
        $posts = $this->repository->all();
		...
    }

}
```

根据标准获取结果集

```php
$posts = $this->repository->getByCriteria(new MyCriteria());
```

在你的仓库中设置默认的标准（条件）类

```php
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    public function boot(){
        $this->pushCriteria(new MyCriteria());
        // or
        $this->pushCriteria(AnotherCriteria::class);
        ...
    }

    function model(){
       return "App\\Post";
    }
}
```

### Skip criteria defined in the repository --跳过仓库中定义的标准

在使用其他任何方法之前使用`skipCriteria`

```php
$posts = $this->repository->skipCriteria()->all();
```

### Popping criteria --弹出标准

使用`popCriteria`删除一个标准

```php
$this->repository->popCriteria(new Criteria1());
// or
$this->repository->popCriteria(Criteria1::class);
```


### Using the RequestCriteria --使用请求标准

请求标准是一个标准的实现，它使用滤器在从请求发送的参数中执行。

你可以执行一个动态搜索，过滤数据和自定义查询。

要使用你仓库中的标准，你可以在你的仓库的`boot`方法中添加一个新的标准，或直接使用在你的控制器中，为了过滤掉少数的请求

####Enabling in your Repository --在你的仓库中使用

```php
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;


class PostRepository extends BaseRepository {

	/**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email'
    ];

    public function boot(){
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        ...
    }

    function model(){
       return "App\\Post";
    }
}
```

注意：你定义的字段在模型中可以搜索到。

在你的仓库中设置**$fieldSearchable**通过字段名或与字段的关系来进行搜索

```php
protected $fieldSearchable = [
	'name',
	'email',
	'product.name'
];
```

你可以设置默认执行查询的条件类型，默认为"**=**"

```php
protected $fieldSearchable = [
	'name'=>'like',
	'email', // Default Condition "="
	'your_field'=>'condition'
];
```


####Enabling in your Controller --在你的控制器中使用

```php
	public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $posts = $this->repository->all();
		...
    }
```

#### Example the Criteria --例如以下标准

请求所有的数据不需要过滤器

`http://prettus.local/users`

```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum",
        "email": "lorem@ipsum.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 3,
        "name": "Laravel",
        "email": "laravel@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

根据请求在仓库中进行搜索

`http://prettus.local/users?search=John%20Doe`

or

`http://prettus.local/users?search=John&searchFields=name:like`

or

`http://prettus.local/users?search=john@gmail.com&searchFields=email:=`

or

`http://prettus.local/users?search=name:John Doe;email:john@gmail.com`

or

`http://prettus.local/users?search=name:John;email:john@gmail.com&searchFields=name:like;email:=`

```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

过滤字段

`http://prettus.local/users?filter=id;name`

```json
[
    {
        "id": 1,
        "name": "John Doe"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 3,
        "name": "Laravel"
    }
]
```

结果集排序

`http://prettus.local/users?filter=id;name&orderBy=id&sortedBy=desc`

```json
[
    {
        "id": 3,
        "name": "Laravel"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 1,
        "name": "John Doe"
    }
]
```

通过相关表进行排序

`http://prettus.local/users?orderBy=posts|title&sortedBy=desc`

Query will have something like this

查询语句中将有：

```sql
...
INNER JOIN posts ON users.post_id = posts.id
...
ORDER BY title
...
```

`http://prettus.local/users?orderBy=posts:custom_id|posts.title&sortedBy=desc`

Query will have something like this

```sql
...
INNER JOIN posts ON users.custom_id = posts.id
...
ORDER BY posts.title
...
```


添加关联关系

`http://prettus.local/users?with=groups`



####Overwrite params name --覆盖参数名称

你可以在配置中更改参数的名称，文件**config/repository.php**中

### Cache  --缓存

轻松添加一层缓存到你的仓库中

#### Cache Usage  --缓存的使用

实现接口`CacheableInterface`和使用`CacheableRepository` Trait

```php
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

class PostRepository extends BaseRepository implements CacheableInterface {

    use CacheableRepository;

    ...
}
```

这样做，你的存储库将被缓存，当创建一条新纪录时，缓存将被清除（修改或删除）

#### Cache Config  --缓存配置

你还可以在 *config/repository.php* 中更改缓存设置，也可以直接在你的仓库中进行修改

*config/repository.php*

```php
'cache'=>[
    //Enable or disable cache repositories 是否使用
    'enabled'   => true,

    //Lifetime of cache 缓存时间（分钟）
    'minutes'   => 30,

    //Repository Cache, implementation Illuminate\Contracts\Cache\Repository
	//仓库缓存，实现Illuminate\Contracts\Cache\Repository
    'repository'=> 'cache',

    //Sets clearing the cache  设置清除缓存
    'clean'     => [
        //Enable, disable clearing the cache on changes
        'enabled' => true,

        'on' => [
            //Enable, disable clearing the cache when you create an item
            'create'=>true,

            //Enable, disable clearing the cache when upgrading an item
            'update'=>true,

            //Enable, disable clearing the cache when you delete an item
            'delete'=>true,
        ]
    ],
    'params' => [
        //Request parameter that will be used to bypass the cache repository
		//将用于绕过缓存库的请求参数
        'skipCache'=>'skipCache'
    ],
    'allowed'=>[
        //Allow caching only for some methods
		//只允许一些方法缓存
        'only'  =>null,

        //Allow caching for all available methods, except
		//排除一些方法不进行缓存
        'except'=>null
    ],
],
```

它是可以直接在仓库中覆盖这些设置的

```php
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

class PostRepository extends BaseRepository implements CacheableInterface {

    // Setting the lifetime of the cache to a repository specifically
    protected $cacheMinutes = 90;

    protected $cacheOnly = ['all', ...];
    //or
    protected $cacheExcept = ['find', ...];

    use CacheableRepository;

    ...
}
```

缓存方法可选值：all, paginate, find, findByField, findWhere, getByCriteria

### Validators  --验证器

Requires [prettus/laravel-validator](https://github.com/prettus/laravel-validator). `composer require prettus/laravel-validator`

轻松验证 `prettus/laravel-validator`

[For more details click here](https://github.com/prettus/laravel-validator)

#### Using a Validator Class --使用验证器

##### Create a Validator --创建一个验证器

在下面的列子中，我们定义了一些创建和版本的规则

```php
use \Prettus\Validator\LaravelValidator;

class PostValidator extends LaravelValidator {

    protected $rules = [
        'title' => 'required',
        'text'  => 'min:3',
        'author'=> 'required'
    ];

}
```

定义特定的规则，如下：

```php
use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class PostValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'text'  => 'min:3',
            'author'=> 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required'
        ]
   ];

}
```

##### Enabling Validator in your Repository --在你的仓库中使用验证器

```php
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class PostRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model(){
       return "App\\Post";
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return "App\\PostValidator";
    }
}
```
 
#### Defining rules in the repository --在仓库中定义验证规则

另外：不是使用类来定义它的验证规则，您可以将您的规则设置到仓库属性中，它与验证类相同的结果

```php
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;

class PostRepository extends BaseRepository {

    /**
     * Specify Validator Rules
     * @var array
     */
     protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title' => 'required',
            'text'  => 'min:3',
            'author'=> 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title' => 'required'
        ]
   ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model(){
       return "App\\Post";
    }

}
```

验证已经准备好了，在失败的情况下将抛出一个异常：*Prettus\Validator\Exceptions\ValidatorException*

### Presenters --呈现器

呈现器作为一个对象包装和渲染

#### Fractal Presenter  --分形呈现器

Requires [Fractal](http://fractal.thephpleague.com/). `composer require league/fractal`

有两种方法来实现呈现器，首先是创建一个转换器并将它用你的呈现器中创建一个转换器类

第二种方法是使你的模型继承转换器接口（Transformable），使用默认的呈现器ModelFractarPresenter，两种方法产生的结果是相同的

##### Transformer Class  --转换器类

###### Create a Transformer using the command  --创建一个转换器使用一下命令

```terminal
php artisan make:transformer Post
```

这将生成下面的类

###### Create a Transformer Class  --创建转换器类

```php
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(\Post $post)
    {
        return [
            'id'      => (int) $post->id,
            'title'   => $post->title,
            'content' => $post->content
        ];
    }
}
```

###### Create a Presenter using the command  --创建一个呈现器使用以下命令

```terminal
php artisan make:presenter Post
```

如果你还没有创建转换器，该命令会提示你安装一个转换器

###### Create a Presenter  --创建一个呈现器

```php
use Prettus\Repository\Presenter\FractalPresenter;

class PostPresenter extends FractalPresenter {

    /**
     * Prepare data to present
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PostTransformer();
    }
}
```

###### Enabling in your Repository  --在你的仓库中使用

```php
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    ...

    public function presenter()
    {
        return "App\\Presenter\\PostPresenter";
    }
}
```

或者在你的控制器中使用

```php
$this->repository->setPresenter("App\\Presenter\\PostPresenter");
```

###### Using the presenter after from the Model  --在模型后使用呈现器

如果你记录了一个呈现器有时用` skippresenter() `方法或者你不想你的结果是不是由呈现器自动改变

你可以在你的模型实现像样的界面让你可以在任何时候提出你的模型，如下：

在你的模型中实现`Prettus\Repository\Contracts\Presentable`接口和使用`Prettus\Repository\Traits\PresentableTrait` Trait

```php
namespace App;

use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;

class Post extends Eloquent implements Presentable {

    use PresentableTrait;

    protected $fillable = [
        'title',
        'author',
        ...
     ];

     ...
}
```

在这里，现在你可以单独提交你的模型，如下：

```php
$repository = app('App\PostRepository');
$repository->setPresenter("Prettus\\Repository\\Presenter\\ModelFractalPresenter");

//Getting the result transformed by the presenter directly in the search
//获取又呈现器在搜索中转化的结果
$post = $repository->find(1);

print_r( $post ); //It produces an output as array 它产生一个输出为数组

...

//Skip presenter and bringing the original result of the Model

//跳过呈现器，输出Model的原始结果集
$post = $repository->skipPresenter()->find(1);
 
print_r( $post ); //It produces an output as a Model object 它产生一个输出为Model对象
print_r( $post->presenter() ); //It produces an output as array 它产生一个输出为数组

```

您可以在每一次访问跳过呈现器，并使用它直接到模型的需求，在你的仓库中设置`$skipPresenter`属性为true

```php
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    /**
    * @var bool
    */
    protected $skipPresenter = true;

    public function presenter()
    {
        return "App\\Presenter\\PostPresenter";
    }
}
```

##### Model Class  --模型类

###### Implement Interface  --实现接口

```php
namespace App;

use Prettus\Repository\Contracts\Transformable;

class Post extends Eloquent implements Transformable {
     ...
     /**
      * @return array
      */
     public function transform()
     {
         return [
             'id'      => (int) $this->id,
             'title'   => $this->title,
             'content' => $this->content
         ];
     }
}
```

###### Enabling in your Repository  --在你的仓库中使用

`Prettus\Repository\Presenter\ModelFractalPresenter` 是一个实现模型转换默认呈现器

```php
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    ...

    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }
}
```

或在你的控制器中使用

```php
$this->repository->setPresenter("Prettus\\Repository\\Presenter\\ModelFractalPresenter");
```

### Skip Presenter defined in the repository  在仓库中跳过呈现器定义

在任何其他方法之前使用*skipPresenter*

```php
$posts = $this->repository->skipPresenter()->all();
```

or

```php
$this->repository->skipPresenter();

$posts = $this->repository->all();
```
