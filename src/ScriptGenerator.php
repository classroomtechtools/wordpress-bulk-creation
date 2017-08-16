<?php

namespace ClassroomTechTools\WordpressBulkCreation;

use ClassroomTechTools\WordpressBulkCreation\Models\Student;

class ScriptGenerator
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var HomeRoomCalculator
     */
    private $homeRoomCalculator;

    /**
     * @param Config $config
     * @param HomeRoomCalculator $homeRoomCalculator
     */
    public function __construct(Config $config, HomeRoomCalculator $homeRoomCalculator)
    {
        $this->config = $config;
        $this->homeRoomCalculator = $homeRoomCalculator;
    }

    /**
     * @param Student[] $students
     *
     * @return string
     */
    public function generateWordpressScriptForStudents(array $students)
    {
        $str = '';

        $createForGrades = [3, 4, 5];

        foreach ($students as $student) {
            if (!in_array($student->getGradeLevel(), $createForGrades)) {
                continue;
            }


            if ($student->getPowerSchoolId() != '57702') {
                // For testing
                continue;
            }

            $str .= $this->generateWordpressScriptForStudent($student);
        }

        return $str;
    }

    /**
     * @param Student $student
     *
     * @return string
     */
    public function generateWordpressScriptForStudent(Student $student)
    {
        $wpcli = $this->getWpCliCommand();

        $email = $student->getEmail() ?: $student->getPowerSchoolId().'@mail.ssis-suzhou.net';
        $nameSlug = strtolower(str_replace(' ', '', $student->getFirstName()));

        $blogTitle = "{$student->getFirstName()} {$student->getLastName()}";
        $blogSlug = $nameSlug.$student->getPowerSchoolId();
        $blogUrl = $this->config->getWordpressUrl().$blogSlug;

        $str = "echo {$blogSlug} {$student->getHomeRoom()} {$student->getLtisUsername()}".PHP_EOL;

        $str .= "NEW=false;".PHP_EOL;

        $str .= "{$wpcli} blog create --slug='{$blogSlug}' --title='{$blogTitle}' --email='{$email}' && NEW=true".PHP_EOL;

        $str .= 'echo $NEW'.PHP_EOL;

        // Run these commands on every blog.
        $alwaysCommands = [
            "user set-role {$email} editor",
            "option update blogdescription \"My Blogfolio, My Learning\"",
            "option set akismet_strictness 1",
            "option set comment_moderation 1",
            "option set comment_whitelist 0",
            "option set moderation_notify 0",
            "option set comments_notify 0",
            "option set default_category 2",
            "option set category_base category",
            "option set tag_base '/tag'",
            "plugin activate subscribe2",
        ];

        $homeRoomTeacher = $this->homeRoomCalculator->getHomeRoomTeacherForStudent($student);
        if ($homeRoomTeacher) {
            $alwaysCommands[] = "user set-role {$homeRoomTeacher->getEmail()} author";
        }

        $replectionPromptsSrc = dirname(__DIR__)."/templates/reflection-prompts.txt";

        $headerImageUrl = 'https://portfolios.ssis-suzhou.net/adam99999/wp-content/uploads/sites/11/2017/08/cropped-DJI_0002.jpg';
        $headerImageData = [
            "attachment_id" => 12,
            "url" => $headerImageUrl,
            "thumbnail_url" => $headerImageUrl,
            "height" => 215,
            "width" => 825,
        ];

        $responsibleUseUrl = 'https://dragonnet.ssis-suzhou.net/pluginfile.php/211543/mod_label/intro/Responsible%20Use%20-%20PYP%20Student%20Agreement.pdf';

        // Only run these commands on newly created blogs.
        $newCommands = [
            // Should be post 3
            "post create --post_type-page --post_title='Reflection Prompts' {$replectionPromptsSrc} --post_status='publish' --post_name='reflection-prompts'",

            // Delete the 'Sample Page'
            "post delete 2 --force",

            // Delete the comment on the first post.
            "comment delete 1 --force",

            // Fix the title of the first post. Editing this post creates post 4
            "post update 1 --post_title='Welcome to Your Blogfolio' --post_name='welcome-to-your-blogfolio'",

            "theme activate twentytwelve",
            "theme mod set header_textcolor 515151",
            "theme mod set header_image '{$headerImageUrl}'",
            "theme mod set header_image_data '".json_encode($headerImageData)."'",
            "theme mod set background_color ffffff",
            "term delete category 1",
            "term create category Homeroom",
            "term create category Art",
            "term create category Music",
            "term create category PE",
            "post term remove 1 category Uncategorized",
            "post term add 1 category Homeroom",
            "post term add 1 category Art",
            "post term add 1 category Music",
            "post term add 1 category PE",
            "widget deactivate search-2",
            "widget move categories-2 --position=1",
            "widget deactivate meta-2",
            "widget deactivate archives-2",
            "widget add tag_cloud sidebar-1 2 --title='Post tags' --taxonomy='post_tag'",
            "widget add s2_form_widget sidebar-1 5 --title='Subscribe to my Blogfolio!' --div=search --size=20",

            "menu create 'Main Menu'",
            "menu location assign main-menu primary",
            "menu item add-custom main-menu Home {$blogUrl}",
            "menu item add-post main-menu 3", // Add Reflection Prompts link
            "menu item add-custom main-menu 'Responsible Use Policy' {$responsibleUseUrl}",
        ];

        foreach ($alwaysCommands as $command) {
            $str .= "{$wpcli} --url={$blogUrl} {$command}".PHP_EOL;
        }

        foreach ($newCommands as $command) {
            $str .= '$NEW'." && {$wpcli} --url={$blogUrl} {$command}".PHP_EOL;
        }

        // Fix usernames while we're at it.
        $str .= "{$wpcli} user update {$email} "
            ." --first_name='{$student->getFirstName()}'"
            ." --last_name='{$student->getLastName()}'"
            ." --display_name='{$student->getFirstName()}'".PHP_EOL;

        return $str;
    }

    /**
     * @return string
     */
    protected function getWpCliCommand()
    {
        return "{$this->config->getWordpressCliBin()} --path={$this->config->getWordpressPath()}";
    }
}
