<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$app->get('/hello/{name}', function ($name, $row) {
    global $row;
    global $courses;
    $arrCourses = array();
    // parsing csv
    // generate data.js with courses
    $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'csv';
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            $row = 1;
            if ($file != "." && $file != "..") {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                if (in_array($file, array('NorthEast Petroleum Unversity, China.csv','China University of Mining and Technology.csv'))) {
                    $country = 'China';
                } else {
                    $country = 'Russia';
                }
                echo 'parser file ' . $file . PHP_EOL;
                parseCsv($filePath, $country);
            }
        }
        closedir($handle);
    }

    $js = 'var courses = [];' . PHP_EOL;

    foreach ($courses as $course) {
        $js .= "course = {" . PHP_EOL;
        foreach ($course as $property => $value) {
            $js .= "$property : '$value',". PHP_EOL;
        }
        $js .= "};". PHP_EOL;
        $js .= "courses.push(course);" . PHP_EOL;
    }
    file_put_contents('data.js', $js);

    return "Hello $dir!";
});

/**
 * @param $filePath
 */
function parseCsv($filePath, $country = 'Russia') {
    global $row;
    global $courses;
    $assFields = array(
        'Course Name' => 'name',
        'Language' => 'lang',
        'Duration' => 'duration',
        'Degree Level' => 'degree_level',
        'Accommodation fee' => 'accommodation_fee',
        'Tuition fee' => 'tuition_fee',
        'Total Fee' => 'total_fee',
        'Product Name' => 'name',
        'Subject Area' => 'subject_area',
        'Subject' => 'subject',
        'Accommodation Amount' => 'accommodation_fee',
        'Tution Amount' => 'tuition_fee',
        'Overall Fees' => 'total_fee',
        'Courses name' => 'name',
        'Total fee' => 'total_fee',
        'Language of instruction' => 'lang',
        'Level of education' => 'degree_level',
        'Accommodation  fee' => 'accommodation_fee',
        'Tuition  Fee' => 'tuition_fee',
        'Tution Fees' => 'tuition_fee',
        'Accommodation' => 'accommodation_fee',
    );
    $numberRow = 0;
    if (($handle = fopen($filePath, "r")) !== FALSE) {
        $fieldsAss = array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($numberRow == 0) {
                foreach ($data as $index => $value) {
                    $value = trim($value);
                    if (array_key_exists($value, $assFields)) {
                        $fieldsAss[$assFields[$value]] = $index;
                    }
                }
                $numberRow++;
                continue;
            }
            $numberRow++;
            if (!isEmptyArray($data)) {
                $row++;
                $course = array();
                foreach ($data as $index => $value) {
                    $key = array_search($index, $fieldsAss);
                    if (isset($key) && !empty($key)) {
                        $value = preg_replace("/\r|\n/", "", $value);
                        $course[$key] = trim($value);
                    }
                }
                $course['country'] = $country;
                $courses[] = $course;
            }
            $numberRow++;
        }
        fclose($handle);
    }
}

/**
 * Check that array is empty
 * @param $array
 * @return bool
 */
function isEmptyArray($array) {
    $count = 0;
    $countEmptyValues = 0;
    foreach ($array as $value) {
        if (empty($value)) {
            $countEmptyValues++;
        } else {
            $count++;
        }
    }
    return ($countEmptyValues > $count);
}

return $app;
