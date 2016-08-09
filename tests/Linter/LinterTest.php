<?php

namespace HexletPsrLinter\Linter;

use HexletPsrLinter\Checks\CheckInterface;
use function HexletPsrLinter\makeLinter;
use HexletPsrLinter\Checks\SideEffectsCheck;
use HexletPsrLinter\Visitor\GetChecksVisitor;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class LinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterLintGood()
    {
        $linter = makeLinter();
        $codeGood = file_get_contents(__DIR__ . "/../fixtures/good/good.php");
        $result = $linter($codeGood, []);
        $this->assertEquals($result['result'], []);
    }

    public function testLinterLintIncludedChecks()
    {
        $params = [];
        include_once __DIR__.'/../fixtures/checks/VariableLengthCheck.php';
        $params['includeChecks'][] = new \OthersPsrLinter\Checks\VariableLengthCheck();

        $linter = makeLinter();
        $codeGood = file_get_contents(__DIR__ . "/../fixtures/good/vars.php");
        $result = $linter($codeGood, $params);

        $this->assertNotEquals([], $result['result']);
    }

    public function testLinterLintBad()
    {
        $linter = makeLinter();
        $codeBad = file_get_contents(__DIR__ . "/../fixtures/bad/bad.php");
        $result = $linter($codeBad, []);
        $this->assertNotEquals($result['result'], []);
    }

    public function testGetCheckVisitorGood()
    {
        $file = __DIR__ . "/../fixtures/checks/VariableLengthCheck.php";
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $getChecksVisitor = new GetChecksVisitor();
        $traverser = new NodeTraverser();
        $traverser->addVisitor($getChecksVisitor);
        $codeFile = file_get_contents($file);
        $stmts = $parser->parse($codeFile);
        $traverser->traverse($stmts);
        $this->assertTrue($getChecksVisitor->isClassExists());
        $loadClassName = $getChecksVisitor->getFullClassName();
        include_once($file);
        $this->assertEquals('OthersPsrLinter\Checks\VariableLengthCheck', $loadClassName);
        $this->assertTrue(in_array(CheckInterface::class, class_implements($loadClassName)));
    }

    public function testGetCheckVisitorBad()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $getChecksVisitor = new GetChecksVisitor();
        $traverser = new NodeTraverser();
        $traverser->addVisitor($getChecksVisitor);
        $codeFile = file_get_contents(__DIR__ . "/../fixtures/good/TestClass.php");
        $stmts = $parser->parse($codeFile);
        $traverser->traverse($stmts);
        $this->assertTrue($getChecksVisitor->isClassExists());
        $loadClassName = $getChecksVisitor->getFullClassName();
        include_once(__DIR__ . "/../fixtures/good/TestClass.php");
        $this->assertFalse(in_array(CheckInterface::class, class_implements($loadClassName)));
    }
}
