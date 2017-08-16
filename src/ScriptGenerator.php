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

        $firstPostPath = dirname(__DIR__).'/first-post.txt';

        $str = "echo {$blogSlug} {$student->getHomeRoom()} {$student->getLtisUsername()}".PHP_EOL;

        $str .= "{$wpcli} blog create --slug='{$blogSlug}' --title='{$blogTitle}' --email='{$email}'".PHP_EOL;

        $headerImageUrl = 'https://portfolios.ssis-suzhou.net/adam99999/wp-content/uploads/sites/11/2017/08/cropped-DJI_0002.jpg';
        $headerImageData = [
            "attachment_id" => 12,
            "url" => $headerImageUrl,
            "thumbnail_url" => $headerImageUrl,
            "height" => 215,
            "width" => 825,
        ];

        $commands = [
            "option update blogdescription \"My Blogfolio, My Learning\"",
            //"post create --user=mattives@ssis-suzhou.net --post_title='Welcome to Your Blogfolio' --post_status=publish {$firstPostPath}",
            //"post delete 2 --force",
            //"post delete 1 --force",
            "post update 1 --post_title='Welcome to Your Blogfolio'",
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
            "post term remove 3 category Uncategorized",
            "post term add 1 category Homeroom",
            "post term add 1 category Art",
            "post term add 1 category Music",
            "post term add 1 category PE",
            "widget deactivate search-2",
            "widget move categories-2 --position=1",
            "widget deactivate meta-2",
            "widget deactivate archives-2",
            "widget add tag_cloud sidebar-1 2 --title='Post tags' --taxonomy='post_tag'",
            "plugin activate subscribe2",
            "widget add s2_form_widget sidebar-1 5 --title='Subscribe to my Blogfolio!' --div=search --size=20",
            "option set akismet_strictness 1",
            "option set comment_moderation 1",
            "option set comment_whitelist 0",
            "option set moderation_notify 0",
            "option set comments_notify 0",
            "option set default_category 2",
            "option set category_base category",
            "option set tag_base '/tag'",
            "wp post create --post_type-page --post_title='Reflection Prompts' ".dirname(__DIR__)."/templates/reflection-prompts.txt",
        ];

        $homeRoomTeacher = $this->homeRoomCalculator->getHomeRoomTeacherForStudent($student);
        if ($homeRoomTeacher) {
            $commands[] = "user set-role {$homeRoomTeacher->getEmail()} author";
        }

        foreach ($commands as $command) {
            $str .= "{$wpcli} --url={$blogUrl} {$command}".PHP_EOL;
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
