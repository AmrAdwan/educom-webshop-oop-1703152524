<?php
class HtmlDoc
{
  public $page;
  public function show()
  {
    $this->showHtmlStart();
    $this->showHeaderStart();
    $this->showHeaderContent();
    $this->showHeaderEnd();
    $this->showBodyStart();
    $this->showBodyContent();
    $this->showBodyEnd();
    $this->showHtmlEnd();

  }
  private function showHtmlStart()
  {    
    echo "<!DOCTYPE html>\n";
    echo "<html>";
  }

  private function showHeaderStart()
  {
    echo "<head>";
  }

  protected function showHeaderContent()
  {
    echo "showHeaderContent";
  }

  private function showHeaderEnd()
  {
    echo "</head>";
  }

  private function showBodyStart()
  {
    echo "<body>";
  }

  protected function showBodyContent()
  {

  }

  private function showBodyEnd()
  {
    echo "</body>";
  }

  private function showHtmlEnd()
  {
    echo "</html>";
  }

}

?>