<?php
/**
 * Google Chrome Coverage Export Parser PHP
 *
 * Export a coverage report from Google Chrome:
 * Developer Tools > 3-dot menu > More tools > Coverage
 *
 * Download the coverage report JSON file by clicking the "Export..." icon on
 * the Coverage panel toolbar
 *
 * @usage
 * php coverage-export-parser.php Coverage-YYYYMMDDTHHIISS.json /wp-content/themes/theme-directory/style.css output.css
 */

$json_string = $argv[1] ?: 'Coverage-YYYYMMDDTHHIISS.json';
$target_url = $argv[2] ?: '/wp-content/themes/theme-directory/style.css';
$output_filename = $argv[3] ?: 'output.css';

$jsondata = file_get_contents(sprintf('%s/%s', dirname(__FILE__), $json_string));
$obj = json_decode($jsondata, true);
$output_file = '';

foreach ($obj as $arr) {

  /** Match Coverage item source url with $target_url */
  if (strpos($arr['url'], $target_url) !== false) {

    foreach ($arr['ranges'] as $name => $value) {
      $length = $value['end'] - $value['start'];
      $output_file .= substr($arr['text'], $value['start'], $length) . PHP_EOL;
    }

    break;
  }
}

if ($output_file) {
  echo PHP_EOL;

  /** Echo CSS to terminal */
  echo $output_file;

  /** Output CSS to file */
  file_put_contents($output_filename, $output_file);

  /** Open CSS file in VS Code */
  exec(sprintf('code %s', $output_filename));
}
else {
  echo 'No output' . PHP_EOL;
}
