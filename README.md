# Wordpress Bulk Creation

A utility to read a CSV export of students from PowerSchool and create Wordpress blogs for them.

## Usage

### 1. Configuration
Edit  `config.php` as appropriate.

Edit the file `first-post.txt` with the contents of the first post on the blog.

### 2. Get The Input Data
Setup an 'autosend' from PowerSchool to export all the student info.

### 3. Run
```
php generate.php > create-blogs.sh
chmod +x create-blogs.sh
./create-blogs.sh
```
Should be run as www-data.

### Note
This needs the [wpcli](http://wp-cli.org/) installed.
