<?php

namespace HexletPsrLinter\Checks;

use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class VariableCheck extends AbstractCheck implements CheckInterface
{
    private $errors = [];
    private $nodeType;
    private $regex;
    private $comment;
    private $commentFix;

    public function __construct(
        $nodeType = 'Expr_Variable',
        $regex = '^[a-z]+([A-Z]?[a-z1-9]+)*$',
        $comment = "The variable name no CamelCaps format. ",
        $commentFix = "Variable name corrected to camelСaps format"
    ) {
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
        if ($result == 0) {
            $this->errors[] = [
                'line'      => $node->getLine(),
                'logLevel'  => Report::LOG_LEVEL_ERROR,
                'name'      => $node->name,
                'message'   => $this->comment
            ];

            return false;
        }
        return true;
    }


    public function modification(Node $node) : bool
    {
        if ($this->shouldBeFixed($node->name)) {
            $newNodeName = $this->correctionVariableName($node->name);
            $this->errors[] = [
                'line'      => $node->getLine(),
                'logLevel'  => Report::LOG_LEVEL_INFO,
                'name'      => "{$node->name} => {$newNodeName}",
                'message'   => $this->commentFix
            ];
            $node->name = $newNodeName;
            return true;
        }
        return false;
    }

    private function shouldBeFixed($name)
    {
        return preg_match_all("/^[a-z]+([a-z1-9]+)+(_[a-z1-9]+)+$/", $name) > 0;
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
