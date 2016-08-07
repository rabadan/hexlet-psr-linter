<?php

namespace HexletPsrLinter\Checks;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class FixVariableCheck implements CheckInterface
{
    private $errors = [];
    private $nodeType;
    private $regex;
    private $fix;
    private $comment;
    private $commentFix;

    public function __construct(
        $fix = false,
        $nodeType = 'Expr_Variable',
        $regex = '^[a-z]+([a-z1-9]+)+(_[a-z1-9]+)+$',
        $comment = "The variable name can be automatically changed in CamelCaps format. ".
        "To do this, run linter with the option 'fix'",
        $commentFix = "Variable name corrected to camelСaps format"
    ) {
        $this->fix      = $fix;
        $this->nodeType = $nodeType;
        $this->regex    = $regex;
        $this->comment  = $comment;
        $this->commentFix  = $commentFix;
    }


    public function isAcceptable(Node $node)
    {
        return $node->getType() === $this->nodeType;
    }

    public function validate(Node $node)
    {
        $result = preg_match_all("/{$this->regex}/", $node->name);
        if ($result > 0) {
            if ($this->fix) {
                $newName = $this->correctionVariableName($node->name);
                $this->errors = new Message(
                    $node->getLine(),
                    Report::LOG_LEVEL_FIXED,
                    "{$node->name} => {$newName}",
                    $this->commentFix
                );
                $node->name = $newName;
                return false;
            }

            $this->errors = new Message(
                $node->getLine(),
                Report::LOG_LEVEL_WARNING,
                $node->name,
                $this->comment
            );

            return false;
        }
        return true;
    }

    public function correctionVariableName($name)
    {
        $pos = strpos($name, '_', 1);

        if (($pos !== false) && $pos<(strlen($name)-1)) {
            $name = substr($name, 0, $pos).ucfirst(substr($name, $pos+1));
            $name = $this->correctionVariableName($name);
        }

        return $name;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
