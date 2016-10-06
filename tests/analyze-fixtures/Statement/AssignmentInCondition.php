<?php

namespace Tests\Analyze\Fixtures\Statement;

class AssignmentCondition
{
    public function testAssignmentIf()
    {
        $a = 2;
        if ($a = 1) {
            return true;
        } elseif ($a = 2) {
            return false;
        } elseif ($a = 3) {
            return false;
        }
    }

    public function testAssignmentWhile()
    {
        $a = 1;
        while ($a = 2) {
            echo $a;
        }

        do {
            echo $a;
        } while ($a = 2);
    }

    public function testAssignmentSwitch()
    {
        $x = 2;
        switch (true) {
            case ($x = 1):
                break;
            case ($x = 2):
                break;
        }
    }

    public function testNoAssignmentIf()
    {
        $a = 1;
        if ($a == 1) {
            return true;
        } elseif ($a == 3) {
            return false;
        }
    }

    public function testNoAssignmentWhile()
    {
        $a = 1;
        while ($a == 1) {
            echo $a;
        }
    }

    public function testNoAssignmentSwitch()
    {
        $x = 2;
        switch (true) {
            case ($x == 1):
                break;
        }
    }
}

?>
----------------------------
[
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 9
    },
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 11
    },
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 13
    },
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 21
    },
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 25
    },
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 34
    },
    {
        "type": "assignment_in_condition",
        "message": "An assignment statement has been made instead of conditional statement",
        "file": "AssignmentInCondition.php",
        "line": 36
    }
]
