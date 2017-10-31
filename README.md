## Description
Easy handle APlayer on WordPress. A shortcode for WordPress to using APlayer.

Support `[audio]` tag, compatible with AMP.

<a href="https://github.com/kn007/APlayerHandle/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-green.svg?style=flat"></a>

## Requirement

* WordPress
* APlayer

## How To Using

Put `class.aplayer.php` into your theme folder, then put this following code to your theme `functions.php`:
```
class_exists('APlayerHandle') or require(get_template_directory() . '/class.aplayer.php');
$aplayer = new APlayerHandle;
$aplayer->init();
```


## Thank you list

DIYgod, the author of APlayer. [Github repo](https://github.com/MoePlayer/APlayer)

## About

[kn007's blog](https://kn007.net) 

***

## 中文说明
轻松的在WordPress使用上APlayer，短代码形式调用。

支持原生`[audio]`标签，不影响AMP模式。

<a href="https://github.com/kn007/APlayerHandle/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-green.svg?style=flat"></a>

## 依赖组件

* WordPress
* APlayer

## 如何使用

将`class.aplayer.php`放在你主题的根目录下，然后将下面代码放在`functions.php`里即可。
```
class_exists('APlayerHandle') or require(get_template_directory() . '/class.aplayer.php');
$aplayer = new APlayerHandle;
$aplayer->init();
```

## 特别鸣谢

感谢DIYgod写的APlayer，[Github项目地址](https://github.com/MoePlayer/APlayer)

## 关于作者

[kn007的个人博客](https://kn007.net) 
