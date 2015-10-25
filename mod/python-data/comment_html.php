<?php

use \Tsugi\Core\LTIX;
use \Tsugi\Util\LTI;
use \Tsugi\Util\Mersenne_Twister;

$sanity = array(
  'urllib' => 'You should use urllib to retrieve the data from the URL',
  'BeautifulSoup' => 'You should use the BeautifulSoup library to parse the HTML'
);

// Compute the stuff for the output
$code = 42;
$new = getShuffledNames($code);
$nums = getRandomNumbers($code,min(50,count($new)),100);
$sum_sample = array_sum($nums);

$code = $USER->id+$LINK->id+$CONTEXT->id;
$new = getShuffledNames($code);
$nums = getRandomNumbers($code,min(50,count($new)),100);
$sum = array_sum($nums);

$oldgrade = $RESULT->grade;
if ( isset($_POST['sum']) && isset($_POST['code']) ) {
    if ( $_POST['sum'] != $sum ) {
        $_SESSION['error'] = "Your sum did not match";
        header('Location: '.addSession('index.php'));
        return;
    }

    $val = validate($sanity, $_POST['code']);
    if ( is_string($val) ) {
        $_SESSION['error'] = $val;
        header('Location: '.addSession('index.php'));
        return;
    }

    $RESULT->setJsonKey('code', $_POST['code']);

    LTIX::gradeSendDueDate(1.0, $oldgrade, $dueDate);
    // Redirect to ourself
    header('Location: '.addSession('index.php'));
    return;
}

// echo($goodsha);
if ( $LINK->grade > 0 ) {
    echo('<p class="alert alert-info">Your current grade on this assignment is: '.($LINK->grade*100.0).'%</p>'."\n");
}

if ( $dueDate->message ) {
    echo('<p style="color:red;">'.$dueDate->message.'</p>'."\n");
}
$url = curPageUrl();
$sample_url = str_replace('index.php','data/comments_42.html',$url);
$actual_url = str_replace('index.php','data/comments_'.$code.'.html',$url);
?>
<p>
<b>Scraping Numbers from HTML using BeautifulSoup</b>
In this assignment you will write a Python program similar to 
<a href="http://www.pythonlearn.com/code/urllink2.py" target="_blank">http://www.pythonlearn.com/code/urllink2.py</a>.  
The program will use <b>urllib</b> to read the HTML from the data files below, and parse the data, 
extracting numbers and compute the sum of the numbers in the file.
</p>
<p>
We provide two files for this assignment.  One is a sample file where we give you the sum for your
testing and the other is the actual data you need to process for the assignment.  
<ul>
<li> Sample data: <a href="<?= deHttps($sample_url) ?>" target="_blank"><?= deHttps($sample_url) ?>.</a> 
(Sum=<?= $sum_sample ?>) </li>
<li> Actual data: <a href="<?= deHttps($actual_url) ?>" target="_blank"><?= deHttps($actual_url) ?></a> 
(Sum ends with <?= $sum%100 ?>)<br/> </li>
</ul>
You do not need to save these files to your folder since your
program will read the data directly from the URL.
<b>Note:</b> Each student will have a distinct data url for the assignment - so only use your
own data url for analysis.
</p>
<b>Data Format</b>
<p>
The file is a table of names and comment counts.   You can ignore most of the data in the
file except for lines like the following:
<pre>
&lt;tr>&lt;td>Modu&lt;/td>&lt;td>&lt;span class="comments">90&lt;/span>&lt;/td>&lt;/tr>
&lt;tr>&lt;td>Kenzie&lt;/td>&lt;td>&lt;span class="comments">88&lt;/span>&lt;/td>&lt;/tr>
&lt;tr>&lt;td>Hubert&lt;/td>&lt;td>&lt;span class="comments">87&lt;/span>&lt;/td>&lt;/tr>
</pre>
You are to find all the &lt;span&gt; tags in the file and pull out the numbers from the 
tag and sum the numbers.
<p>
Look at the 
<a href="http://www.pythonlearn.com/code/urllink2.py" target="_blank">sample code</a>
provided.  It shows how to find all of a certain kind of tag, loop through the tags and
extract the various aspects of the tags.
<pre>
...
# Retrieve all of the anchor tags
tags = soup('a')
for tag in tags:
   # Look at the parts of a tag
   print 'TAG:',tag
   print 'URL:',tag.get('href', None)
   print 'Contents:',tag.contents[0]
   print 'Attrs:',tag.attrs
</pre>
You need to adjust this code to look for <b>span</b> tags and pull out 
the text content of the span tag, convert them to integers and 
add them up to complete the assignment.
</p>

<p><b>Turning in the Assignment</b>
<form method="post">
Enter the sum from the actual data and your Python code below:<br/>
Sum: <input type="text" size="20" name="sum">
(ends with <?= $sum%100 ?>)
<input type="submit" value="Submit Assignment"><br/>
Python code:<br/>
<textarea rows="20" style="width: 90%" name="code"></textarea><br/>
</form>