<?php

class SearchManager
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    private function camelCaseToWords($str)
    {
        $str = strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', " $1", $str));
        return ucfirst($str);
    }
    public function php_search_all_database($search_keyword, $table_associative_array)
    {
        $count = 0;

        if (mysqli_connect_errno()) {        // Check if database connection is ok
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        if (count($table_associative_array) > 0) {
            foreach ($table_associative_array as $table_name => $columnn_name) {
                foreach ($columnn_name as $column) {
                    $stmt = $this->conn->prepare("SELECT * FROM " . $table_name . " WHERE " . $column . " LIKE ?");
                    $param = '%' . $search_keyword . '%';
                    $stmt->bind_param("s", $param);
                    $stmt->execute();
                    $db_search_result = $stmt->get_result();

                    if ($db_search_result->num_rows > 0) {
                        while ($row = $db_search_result->fetch_array()) {

                            $columnName = $this->camelCaseToWords($column);

                            echo '<div class="box">';
                            if (($table_name == 'admins') or ($table_name == 'users')) {
                                echo '<div class="breaking"><p> Page: <span><a href="users.php"> Users
                                </a></span></p></div>';
                            } else if ($table_name == 'requests') {
                                echo '<div class="breaking"><p> Page: <span><a href="admin_page.php"> Requests
                                </a></span></p></div>';
                            } else if ($table_name == 'departments') {
                                echo '<div class="breaking"><p> Page: <span><a href="departments.php"> Departments
                                </a></span></p></div>';
                            } else if ($table_name == 'tickets') {
                                echo '<div class="breaking"><p> Page: <span><a href="admin_tickets.php"> Tickets
                                </a></span></p></div>';
                            } else if ($table_name == 'ticket_types') {
                                echo '<div class="breaking"><p> Page: <span><a href="tck_types.php"> Ticket types
                                </a></span></p></div>';
                            }

                            echo '<div class="breaking"><p>Column name: <span>' . $columnName . "</span></p></div>";
                            echo '<div class="breaking"><p>Row ID: <span>' . $row[0] . "</span></p></div>";
                            echo '<div class="breaking"><p>Value: <span>' . $row[$column] . "</span></p></div>";
                            echo '</div>';
                        }
                    } else {
                        if ($db_search_result->num_rows == 0) {
                            $count++;
                        }
                    }
                    $stmt->close();
                }
            }
        }

        if ($count >= 24) {
            return 0;
        } else {
            return 1;
        }
    }

    public function resultsFound($search_keyword, $table_associative_array)
    {
        $count = 0;

        if (mysqli_connect_errno()) {        // Check if database connection is ok
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        if (count($table_associative_array) > 0) {
            foreach ($table_associative_array as $table_name => $columnn_name) {
                foreach ($columnn_name as $column) {
                    $stmt = $this->conn->prepare("SELECT * FROM " . $table_name . " WHERE " . $column . " LIKE ?");
                    $param = '%' . $search_keyword . '%';
                    $stmt->bind_param("s", $param);
                    $stmt->execute();
                    $db_search_result = $stmt->get_result();

                    if ($db_search_result->num_rows > 0) {
                        $stmt->close();
                        return true;
                    } else {
                        if ($db_search_result->num_rows == 0) {
                            $count++;
                        }
                    }
                    $stmt->close();
                }
            }
        }

        if ($count >= 24) {
            return false;
        } else {
            return true;
        }
    }
}