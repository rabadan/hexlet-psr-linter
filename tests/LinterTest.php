<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {

        $linter = new Linter();
        $result = $linter->run(["path"=>__DIR__."/fixtures/good"]);
        $this->assertEquals($result, [
            __DIR__."/fixtures/good/TestClass.php"=>[],
            __DIR__."/fixtures/good/good.php"=>[],
        ]);

        $result = $linter->run(["path"=>__DIR__."/fixtures/bad"]);
        $this->assertArrayHasKey(__DIR__."/fixtures/bad/bad.php", $result);
        $this->assertContains(3, $result[__DIR__."/fixtures/bad/bad.php"][0]);
        $this->assertContains("Name", $result[__DIR__."/fixtures/bad/bad.php"][0]);
        $this->assertContains(8, $result[__DIR__."/fixtures/bad/bad.php"][1]);
        $this->assertContains("NameGood", $result[__DIR__."/fixtures/bad/bad.php"][1]);
        $this->assertContains(13, $result[__DIR__."/fixtures/bad/bad.php"][2]);
        $this->assertContains("bad_bad_name", $result[__DIR__."/fixtures/bad/bad.php"][2]);
    }

    public function testLint()
    {
        $linter = new Linter();

        $codeGood = "<?php 
        function goodName() {
        }
        function goodname() {
        } 
        function goodNameName() {
        } ";

        $this->assertEquals($linter->lint($codeGood), []);

        $codeGood = "<?php 
        function BadName() {
        }
        function bad_name() {
        } 
        function BadNameName() {
        } ";

        $linter = new Linter();
        $result = $linter->lint($codeGood);
        $this->assertContains(2, $result[0]);
        $this->assertContains("BadName", $result[0]);
        $this->assertContains(4, $result[1]);
        $this->assertContains("bad_name", $result[1]);
        $this->assertContains(6, $result[2]);
        $this->assertContains("BadNameName", $result[2]);
    }
}
