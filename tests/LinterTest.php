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
        $this->assertEquals(
            $result,
            [
                __DIR__."/fixtures/bad/bad.php"=>[
                    0  => [3,"Name"],
                    1  => [8,"NameGood"],
                    2  => [13,"bad_bad_name"],
                ],
            ]
        );
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
        $this->assertEquals($linter->lint($codeGood), [
            0  => [2,"BadName"],
            1  => [4,"bad_name"],
            2  => [6,"BadNameName"],
        ]);
    }
}
