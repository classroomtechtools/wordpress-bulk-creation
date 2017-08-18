# Wordpress Bulk Creation

A utility to read a CSV export of students from PowerSchool and create Wordpress blogs for them.

## Usage

### 1. Configuration
Edit  `config.php` as appropriate.

### 2. Get The Input Data
Setup an 'autosend' from PowerSchool to export all the student info.

### 3. Generate the script to create blogs
```
php generate.php
```

The first time the script is created you need to make it executable
```
chmod +x ./data/create-blogs.sh
```

### 4. Run the script to create blogs
To view the command output and log it at the same time:
(Should be run as www-data, or whoever has permission to empty the wordpress cache directory.)
```
./data/create-blogs.sh | tee ./data/create-blogs.log
```

### Note
This needs the [wpcli](http://wp-cli.org/) installed.
