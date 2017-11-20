# Cakesuit/MetaTable plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require Cakesuit/MetaTable

bin/cake plugin load Cakesuit/MetaTable
```

## How to use it (for example)

Create table user:
```sql
CREATE table users (
    id int(11) auto_increment NOT NULL key,
    username varchar(20) NOT NULL,
    password varchar(60) NOT NULL,
);
```

Create a table meta for user
```sql
CREATE table meta_users (
  id int(11) auto_increment key NOT NULL,
  user_id int(11) NOT NULL,
  meta_key varchar(255) NOT NULL,
  meta_value TEXT NULL
);
```
Insert user row:

| id | username | password |
|-----|:-----:|---------:|
|1|cakesuit|12345|

Insert user meta row:

| id | meta_key | meta_value | user_id |
|-----|:-----:|---------:|:------:|
|1|age|26|1|
|2|sexe|male|1|


Config UsersTable:
```php
<?php

// ...
class UsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

       $this->hasMany('MetaUsers', [
           'foreignKey' => 'user_id',
       ]);
        
       // Add behavior
        $this->addBehavior('Cakesuit/MetaTable.Meta', [
            /**
             * Define meta table name
             * Required: true 
             * Default: null 
             */
            'metaTableName' => 'MetaUsers',
            
            /**
             * Define the name for return de meta
             * Required: false
             * Default: meta
             */
            'propertyName' => 'meta',
            
            /**
             * Define de key column
             * Required: false
             * Default: meta_key
             */
            'keyField' => 'meta_key',
            
            /**
             * Default value
             * Require: false
             * Default Table::getDisplayField()
             */
             'valueField' => 'meta_value',
            
            /**
             * Method for save meta 
             * Required: false
             * Default: true
             * false: insert into meta
             * true: insert into object entities
             * both: meta & object entities
             */
            'addProperties' => 'both',
        ]);
    }
}
```

Fetch meta:
```php
<?php

$usersTable = \Cake\ORM\TableRegistry::get('Users');

$user = $usersTable->get(
    1, 
    [
        'contain' => ['MetaUsers']
    ]);
    

echo $user->username; // cakesuit

// With addProperties (true or both) in behavior config
echo $user->age; // 26
echo $user->sexe; // male

// Without addProperties (false) in behavior config
echo $user->meta->age; // 26
echo $user->meta->sexe; // male
```

## ...

If you encounter any difficulties, contact me. 
Thank you.

***C@kesuit***