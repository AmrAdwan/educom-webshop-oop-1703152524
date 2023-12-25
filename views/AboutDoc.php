<?php
include_once "BasicDoc.php";

class AboutDoc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1> About </h1>";
  }

  protected function showContent()
  {

    echo "<p>My name is Amr Adwan, I am a software developer and am following the traineeship at Educom.
        <br>
        <br>
        I have done so many projects with different programming languages; 
        such as C/C++, Python, C#. I also have experience with web development such as HTML, CSS, JavaScript.
        <br>
        <br>
        I do one of these hobbies in my free time
      </p>";
    echo "<div class=\"hobbies\">
        <ul style=\"list-style-type:square\">
          <li>Chess</li>
          <li>Tennis</li>
          <li>Solve puzzles</li>
          <li>Fitness</li>
        </ul>
      </div>";
  }
}


?>