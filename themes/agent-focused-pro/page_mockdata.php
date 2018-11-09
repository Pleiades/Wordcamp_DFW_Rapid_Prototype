<?php
/**
 * Landing Page template for Agent Focused Pro.
 *
 * @package Agent Focused Pro
 * @author Marcy Diaz for Winning Agent
 * @subpackage Customizations
 */

/*
Template Name: Data Page
*/


spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
    if (file_exists($fileName)) {
        require $fileName;

        return true;
    }

    return false;
});

$faker = Faker\Factory::create();
$faker = \Faker\Factory::create();
// $faker->addProvider(new \FakerRestaurant\Restaurant($faker));
// Generator
// $faker->foodName();      // A random Food Name
// $faker->beverageName();  // A random Beverage Name


?>

<contacts>
<?php for ($i=0; $i < 20; $i++): ?>
  <contact firstName="<?php echo $faker->firstName ?>" lastName="<?php echo $faker->lastName ?>" email="<?php echo $faker->email ?>">
    <phone number="<?php echo $faker->phoneNumber ?>"/>
<?php if ($faker->boolean(25)): ?>
    <birth date="<?php echo $faker->dateTimeThisCentury->format('Y-m-d') ?>" place="<?php echo $faker->city ?>"/>
<?php endif; ?>
    <address>
      <street><?php echo $faker->streetAddress ?></street>
      <city><?php echo $faker->city ?></city>
      <postcode><?php echo $faker->postcode ?></postcode>
      <state><?php echo $faker->state ?></state>
    </address>
    <company name="<?php echo $faker->company ?>" catchPhrase="<?php echo $faker->catchPhrase ?>">
<?php if ($faker->boolean(33)): ?>
      <offer><?php echo $faker->bs ?></offer>
<?php endif; ?>
<?php if ($faker->boolean(33)): ?>
      <director name="<?php echo $faker->name ?>" />
<?php endif; ?>
    </company>
<?php if ($faker->boolean(15)): ?>
    <details>
<?php
?>
<![CDATA[
<?php echo $faker->text(400) ?>
]]>
    </details>
<?php endif; ?>
  </contact>
<?php endfor; ?>
</contacts>

