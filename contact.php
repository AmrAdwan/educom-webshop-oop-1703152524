<?php

function getErrorContact($formResult, $key)
{
  return isset($formResult['errors'][$key]) ? $formResult['errors'][$key] : '';
}
function showContactForm($formResult)
{
  if (!isset($formResult['valid']) || !$formResult['valid'])
  { /* Show the next part only when $valid is false */
    // Extract form data for convenience
    $formData = $formResult['formData'] ?? [];
    echo "<div class=\"formcarry-container\">";
    echo "<form action=\"index.php\" method=\"POST\" class=\"formcarry-form\">";
    echo "<!-- Hidden field to identify the contact form -->";
    echo "<br>";
    echo "<br>";
    echo "<input type=\"hidden\" name=\"page\" value=\"contact\">";
    echo "<select name=\"gender\" id=\"gender\" class=\"select\">";
    echo "<option value=\"\">-Select your Gender-</option>";
    echo "<option value=\"male\"";
    if ($formData['gender'] === 'male')
      echo 'selected=\"selected\"';
    echo ">Male</option>";
    echo "<option value=\"female\"";
    if ($formData['gender'] === 'female')
      echo 'selected=\"selected\"';
    echo ">Female</option>";
    echo "<option value=\"other\"";
    if ($formData['gender'] === 'other')
      echo 'selected=\"selected\"';
    echo ">Other</option>";
    echo "</select>";
    echo "<span class=\"error\">*";
    echo getErrorContact($formResult, 'gender');
    echo "</span>";
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<div class=\"input\">";
    echo "<label for=\"name\">Name</label>";
    echo "<input type=\"text\" id=\"name\" name=\"name\" value=\"" . $formData['name'] ?? '' . "\" />";
    echo "\" />";
    echo "<span class=\"error\">*";
    echo getErrorContact($formResult, 'name');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"email\">Email Address</label>";
    echo "<input type=\"email\" id=\"email\" name=\"email\" value=\"" . $formData['email'] ?? '' . "\" />";
    echo "\"  />";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'email');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"phone\">Phone number</label>";
    echo "<input type=\"tel\" id=\"phone\" name=\"phone\" value=\"" . $formData['phone'] ?? '' . "\" />";
    echo "\" />";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'phone');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<div class=\"wrapper\">";
    echo "<label for=\"address-one\">Street</label>";
    echo "<input autocomplete=\"address-line1\" type=\"text\" id=\"street\" name=\"street\" value=\"" . $formData['street'] ?? '' . "\" />";
    echo "\">";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'street');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"address-one\">House number</label>";
    echo "<input autocomplete=\"address-line1\" type=\" number\" id=\"housenumber\" name=\"housenumber\" value=\"" . $formData['housenumber'] ?? '' . "\" />";
    echo "\">";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'housenumber');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"address-one\">Addition</label>";
    echo "<input autocomplete=\"address-line1\" type=\"text\" id=\"addition\" name=\"addition\" value=\"" . $formData['addition'] ?? '' . "\" />";
    echo "\">";
    echo "</div>"; // Close the <div> for 'wrapper'
    echo "<br>";
    echo "<div>";
    echo "<label for=\"zip\">Zip code</label>";
    echo "<input autocomplete=\"postal-code\" type=\"text\" id=\"zip\" name=\"zip\" value=\"" . $formData['zipcode'] ?? '' . "\" />";
    echo "\">";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'zipcode');
    echo "</span>";
    echo "</div>"; // Close the <div> for 'zip'
    echo "<br>";
    echo "<div>";
    echo "<label for=\"city\">City</label>";
    echo "<input autocomplete=\"address-level2\" type=\"text\" id=\"city\" name=\"city\" value=\"" . $formData['city'] ?? '' . "\" />";
    echo "\">";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'city');
    echo "</span>";
    echo "</div>"; // Close the <div> for 'city'
    echo "<br>";
    echo "<div>";
    echo "<label for=\"province\">Province</label>";
    echo "<input autocomplete=\"address-level1\" type=\"text\" id=\"province\" name=\"province\" value=\"" . $formData['province'] ?? '' . "\" />";
    echo "\">";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'province');
    echo "</span>";
    echo "</div>"; // Close the <div> for 'province'
    echo "<br>";
    echo "<div>";
    echo "<label for=\"country\">Country</label>";
    echo "<input autocomplete=\"country\" type=\"text\" id=\"country\" name=\"country\" value=\"" . $formData['country'] ?? '' . "\" />";
    echo "\">";
    echo "<span class=\"error\">";
    echo getErrorContact($formResult, 'country');
    echo "</span>";
    echo "</div>"; // Close the <div> for 'country'
    echo "<br>";
    echo "<label for=\"message\">Your Message</label>";
    echo "<br>";
    echo "<textarea name=\"message\" id=\"message\" cols=\"30\" rows=\"10\" value=\"" . $formData['message'] ?? '' . "\" />";
    echo "\"></textarea>";
    echo "<span class=\"error\">*";
    echo getErrorContact($formResult, 'message');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<fieldset>";
    echo "<legend>Select the preferred contact method:</legend>";
    echo "<div>";
    echo "<input type=\"radio\" id=\"contactChoice1\" name=\"contact\" value=\"email\"";
    if (isset($formData['contact']) && $formData['contact'] === 'email')
      echo ' checked="checked"';
    echo " />";
    echo "<label for=\"contactChoice1\">Email</label>";
    echo "<input type=\"radio\" id=\"contactChoice2\" name=\"contact\" value=\"phone\"";
    if (isset($formData['contact']) && $formData['contact'] === 'phone')
      echo ' checked="checked"';
    echo " />";
    echo "<label for=\"contactChoice2\">Phone</label>";
    echo "<input type=\"radio\" id=\"contactChoice3\" name=\"contact\" value=\"post\"";
    if (isset($formData['contact']) && $formData['contact'] === 'post')
      echo 'checked="checked"';
    echo " />";
    echo "<label for=\"contactChoice3\">Post</label>";
    echo "<span class=\"error\">*";
    echo getErrorContact($formResult, 'contact');
    echo "</span>";
    echo "</div>"; // Close the <div> for 'fieldset'
    echo "</fieldset>";

    echo "<br>";
    echo "<br>";
    echo "<button type=\"Submit\">Send</button>";
    echo "</form>";
    echo "</div>"; // Close the <div> for 'formcarry-container'
  }
}
// function showContactThanks($formResult)
// { /* Show the next part only when $valid is true */
//   $formData = $formResult['formData'] ?? [];
//   echo "<p>Thank you for your submission:</p>";
//   echo "<h2>Your input:</h2>";
//   echo "<br>";
//   echo "<div>Gender: ";
//   echo $formData['gender'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Name:  ";
//   echo $formData['name'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Email:  ";
//   echo $formData['email'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Phone number:  ";
//   echo $formData['phone'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Street:  ";
//   echo $formData['street'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>House number:  ";
//   echo $formData['housenumber'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Addition:  ";
//   echo $formData['addition'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Zip code:  ";
//   echo $formData['zipcode'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>City  ";
//   echo $formData['city'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Province:  ";
//   echo $formData['province'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Country:  ";
//   echo $formData['country'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Message:  ";
//   echo $formData['message'];
//   echo "</div>";
//   echo "<br>";
//   echo "<div>Contact method:  ";
//   echo $formData['contact'];
//   echo "</div>";
// } /* End of conditional showing */
// echo "</div>";
?>