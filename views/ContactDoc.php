<?php
include_once "FormsDoc.php";
class ContactDoc extends FormDoc
{

  protected function showHeader()
  {
    echo "<h1>Contact</h1>";
  }

  protected function showContent()
  {
    $formResult = $this->model->getData('formResult');
    $this->showForm($formResult);

  }

  protected function getError($formResult, $key)
  {
    return isset($formResult['errors'][$key]) ? $formResult['errors'][$key] : '';
  }

  protected function showForm($formResult = [])
  {
    if (!isset($formResult['valid']) || !$formResult['valid'])
    { /* Show the next part only when $valid is false */
      // Extract form data for convenience
      $formData = ($formResult['formData'] ?? []);
      echo "<div class=\"formcarry-container\">";
      echo "<form action=\"index.php\" method=\"POST\" class=\"formcarry-form\">";
      echo "<!-- Hidden field to identify the contact form -->";
      echo "<br>";
      echo "<br>";
      echo "<input type=\"hidden\" name=\"page\" value=\"contact\">";
      echo "<select name=\"gender\" id=\"gender\" class=\"select\">";
      echo "<option value=\"\">-Select your Gender-</option>";
      echo "<option value=\"male\"";
      // if (($formData['gender']) === 'male')
      if (isset($formData['gender']) && $formData['gender'] === 'male')
        echo 'selected=\"selected\"';
      echo ">Male</option>";
      echo "<option value=\"female\"";
      // if (($formData['gender']) === 'female')
      if (isset($formData['gender']) && $formData['gender'] === 'female')
        echo 'selected=\"selected\"';
      echo ">Female</option>";
      echo "<option value=\"other\"";
      // if (($formData['gender'] === 'other'))
      if (isset($formData['gender']) && $formData['gender'] === 'other')
        echo 'selected=\"selected\"';
      echo ">Other</option>";
      echo "</select>";
      echo "<span class=\"error\">  *";
      echo $this->getError($formResult, 'gender');
      echo "</span>";
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<div class=\"input\">";
      echo "<label for=\"name\">Name</label>";
      echo "<input type=\"text\" id=\"name\" name=\"name\" value=\"" . ($formData['name'] ?? '') . "\" />";
      echo "<span class=\"error\">  *";
      echo $this->getError($formResult, 'name');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"email\">Email Address</label>";
      echo "<input type=\"email\" id=\"email\" name=\"email\" value=\"" . ($formData['email'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'email');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"phone\">Phone number</label>";
      echo "<input type=\"tel\" id=\"phone\" name=\"phone\" value=\"" . ($formData['phone'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'phone');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<div class=\"wrapper\">";
      echo "<label for=\"address-one\">Street</label>";
      echo "<input autocomplete=\"address-line1\" type=\"text\" id=\"street\" name=\"street\" value=\"" . ($formData['street'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'street');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"address-one\">House number</label>";
      echo "<input autocomplete=\"address-line1\" type=\" number\" id=\"housenumber\" name=\"housenumber\" value=\"" . ($formData['housenumber'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'housenumber');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"address-one\">Addition</label>";
      echo "<input autocomplete=\"address-line1\" type=\"text\" id=\"addition\" name=\"addition\" value=\"" . ($formData['addition'] ?? '') . "\" />";
      echo "</div>"; // Close the <div> for 'wrapper'
      echo "<br>";
      echo "<div>";
      echo "<label for=\"zip\">Zip code</label>";
      echo "<input autocomplete=\"postal-code\" type=\"text\" id=\"zip\" name=\"zip\" value=\"" . ($formData['zipcode'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'zipcode');
      echo "</span>";
      echo "</div>"; // Close the <div> for 'zip'
      echo "<br>";
      echo "<div>";
      echo "<label for=\"city\">City</label>";
      echo "<input autocomplete=\"address-level2\" type=\"text\" id=\"city\" name=\"city\" value=\"" . ($formData['city'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'city');
      echo "</span>";
      echo "</div>"; // Close the <div> for 'city'
      echo "<br>";
      echo "<div>";
      echo "<label for=\"province\">Province</label>";
      echo "<input autocomplete=\"address-level1\" type=\"text\" id=\"province\" name=\"province\" value=\"" . ($formData['province'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'province');
      echo "</span>";
      echo "</div>"; // Close the <div> for 'province'
      echo "<br>";
      echo "<div>";
      echo "<label for=\"country\">Country</label>";
      echo "<input autocomplete=\"country\" type=\"text\" id=\"country\" name=\"country\" value=\"" . ($formData['country'] ?? '') . "\" />";
      echo "<span class=\"error\">";
      echo $this->getError($formResult, 'country');
      echo "</span>";
      echo "</div>"; // Close the <div> for 'country'
      echo "<br>";
      echo "<label for=\"message\">Your Message</label>";
      echo "<br>";
      echo "<textarea name=\"message\" id=\"message\" cols=\"30\" rows=\"10\" value=\"" . ($formData['message'] ?? '') . "\" />";
      echo "</textarea>";
      echo "<span class=\"error\">  *";
      echo $this->getError($formResult, 'message');
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
      echo $this->getError($formResult, 'contact');
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
}

?>