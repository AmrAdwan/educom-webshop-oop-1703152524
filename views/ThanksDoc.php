<?php
include_once "BasicDoc.php";
class ThanksDoc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1>Thank You</h1>";
  }

  protected function showContent()
  {
    // Assuming the form data is passed in the model
    $formResult = $this->model->validateContact();

    // Check if formResult is available
    if (!empty($formResult))
    {
      $this->showContactThanks($formResult);
    } else
    {
      echo "<p>No submission data available.</p>";
    }
  }

  private function showContactThanks($formResult)
  {
    // Your existing logic
    $formData = $formResult['formData'] ?? [];
    echo "<h2>Thank you for your submission:</h2>";
    echo "<h3>Your input:</h3>";
    echo "<br>";
    echo "<div>Gender: ";
    echo $formData['gender'];
    echo "</div>";
    echo "<br>";
    echo "<div>Name:  ";
    echo $formData['name'];
    echo "</div>";
    echo "<br>";
    echo "<div>Email:  ";
    echo $formData['email'];
    echo "</div>";
    echo "<br>";
    echo "<div>Phone number:  ";
    echo $formData['phone'];
    echo "</div>";
    echo "<br>";
    echo "<div>Street:  ";
    echo $formData['street'];
    echo "</div>";
    echo "<br>";
    echo "<div>House number:  ";
    echo $formData['housenumber'];
    echo "</div>";
    echo "<br>";
    echo "<div>Addition:  ";
    echo $formData['addition'];
    echo "</div>";
    echo "<br>";
    echo "<div>Zip code:  ";
    echo $formData['zipcode'];
    echo "</div>";
    echo "<br>";
    echo "<div>City  ";
    echo $formData['city'];
    echo "</div>";
    echo "<br>";
    echo "<div>Province:  ";
    echo $formData['province'];
    echo "</div>";
    echo "<br>";
    echo "<div>Country:  ";
    echo $formData['country'];
    echo "</div>";
    echo "<br>";
    echo "<div>Message:  ";
    echo $formData['message'];
    echo "</div>";
    echo "<br>";
    echo "<div>Contact method:  ";
    echo $formData['contact'];
    echo "</div>";
  }
}

?>