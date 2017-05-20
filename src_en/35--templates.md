---
navigation: Templates
title: Templates and Variables
description: Create new or modify an existing Templates
keywords: css, html
---

All templates are located in the ```/themes/``` directory. php{swg} comes with two - ready to use - themes (silver and blackboard).

If you want to create an new theme, I would recommend to duplicate simply one of the default themes - silver or blackboard - and make your changes in this copy.

### Template files

* tpl/index.tpl (required)
* tpl/footer.tpl
* tpl/header.tpl

__Note:__ The complete (selected) Template folder will be copied in the static website‘s directory.

### Available Variables

You can use the variables in both, the templates and in the Markdown files.

* ```{$title}``` The title from your markdown document (Header).
* ```{$description}``` The description from your markdown document (Header).
* ```{$keywords}``` The title from your markdown document (Header).
* ```{$root}``` The Root/Prefix from your Preferences.
* ```{$navigation}``` Will be replaced with the Navigation.
* ```{$content}``` The content from your markdown document.
* ```{$header}``` Will be replaced with the contents from the file __tpl/header.tpl__
* ```{$footer}``` Will be replaced with the contents from the file __tpl/footer.tpl__
* ```{$filename_orig}``` Filename of the markdown file.
* ```{$filepath_orig}``` Complete path to the markdown file.
* ```{$filemtime}``` file modification time (Unix timestamp)
* ```{$filemtime_Y}``` file modification time - Year e.g. 2016
* ```{$filemtime_m}``` file modification time - Month e.g. 02
* ```{$filemtime_d}``` file modification time - Day e.g. 07
* ```{$filemtime_H}``` file modification time - Hour e.g. 11
* ```{$filemtime_i}``` file modification time - Minutes e.g. 45
* ```{$filemtime_s}``` file modification time - Seconds e.g. 33