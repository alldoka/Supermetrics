# Part 3/4. Refactor the following piece of legacy code

> My goal is to show to my future colleagues how I refactor the legacy 

**Refactoring should be done step by step to avoid missing/changing functionality.**

Theoretically I could spend some time to understand what this code was created for and rewrite it from the scratch. But this code looks like a critical one (`final class`?). We should be very carefull, not fast. We could even avoid getting email from `$_REQUEST`.   

## Master email refactoring

Steps:
1. It is a separate function. Let's call it `getMasterEmail()`

2. Short if
```php
$masterEmail = $_REQUEST['email'] ?? null;
```
instead of
```php
if ($_REQUEST['email']) {
    $masterEmail = $_REQUEST['email'];
}
```

3. `!empty`
```php
!empty($masterEmail)
```
instead of
```php
isset($masterEmail) && $masterEmail
```

4. Remove the first line ```$masterEmail = $_REQUEST['email'] ?? null;```
```php
$masterEmail = !empty($_REQUEST['email'])
    ? $_REQUEST['email']
    : array_key_exists('masterEmail', $_REQUEST) && $_REQUEST["masterEmail"]
        ? $_REQUEST['masterEmail'] : 'unknown';
```
instead of
```php
$masterEmail = $_REQUEST['email'] ?? null;

$masterEmail = !empty($masterEmail)
    ? $masterEmail
    : array_key_exists('masterEmail', $_REQUEST) && $_REQUEST["masterEmail"]
        ? $_REQUEST['masterEmail'] : 'unknown';
```

5. `!empty`
```php
!empty($_REQUEST["masterEmail"])
```
instead of
```php
array_key_exists('masterEmail', $_REQUEST) && $_REQUEST["masterEmail"]
```

6. Now the purpose of the code is clear. `WAIT!` Let's make it easy readable for all developers. Sometimes fewer strings, means less value.
```php
public static function getMasterEmail(): string
{ 
   if (!empty($_REQUEST['email'])) {
       return $_REQUEST['email'];
   }
   
   if (!empty($_REQUEST['masterEmail'])) {
       return $_REQUEST['masterEmail'];
   }
   
   return 'unknown';
}
```
instead of
```php
public static function getMasterEmail(): string
{ 
    return !empty($_REQUEST['email']) ? $_REQUEST['email'] : !empty($_REQUEST["masterEmail"]) ? $_REQUEST['masterEmail'] : 'unknown';
}
```

## MySql query

Without a context, it doesn't make sense to write a MySql lib. Here I will only criticize this piece of code.


1. Firstly, we should make an architectural decision regarding technologies for handling the database. It depends on system requirements. Could be Doctrine, PDO, self written library and so on.
2. This code should be separated into two separate classed. One class should manage connections, the second run queries.
3. We should add an abstraction (interface) to be able to change MySql to PostgreSQL for example later
4. Security. We should guard passwords in a secret place and use environment variables instead. The hardcode is always a bad idea.
5. Connection should be closed to avoid memory leaks
6. Exceptions should be handled
7. Security. All input parameters should be sanitized to avoid SQL injection
8. Minor. If we need only the username, then the query should look like `SELECT users.username FROM users WHERE email=?;`
