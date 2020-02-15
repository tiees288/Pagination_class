<?php
class Paginator
{
    var $items_per_page;
    var $items_total;
    var $current_page;
    var $num_pages;
    var $mid_range;
    var $low;
    var $high;
    var $limit;
    var $return;
    var $default_ipp;
    var $querystring;
    var $url_next;

    function Paginator()
    {
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = $this->default_ipp;
        $this->url_next = $this->url_next;
    }
    function paginate()
    {

        if (!is_numeric($this->items_per_page) or $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
        $this->num_pages = ceil($this->items_total / $this->items_per_page);

        if ($this->current_page < 1 or !is_numeric($this->current_page)) $this->current_page = 1;
        if ($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;


        if ($this->num_pages > 10) {
            $this->return = ($this->current_page != 1 and $this->items_total >= 10) ? "<a class=\"paginate\" href=\"" . $this->url_next . $this->$prev_page . "\">&laquo; Previous</a> " : "<span class=\"inactive\" href=\"#\">&laquo; Previous</span> ";

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0) {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages) {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($this->range[0] > 2 and $i == $this->range[0]) $this->return .= " ... ";
                if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                    $this->return .= ($i == $this->current_page and $_GET['Page'] != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"" . $this->url_next . $i . "\">$i</a> ";
                }
                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 and $i == $this->range[$this->mid_range - 1]) $this->return .= " ... ";
            }
            $this->return .= (($this->current_page != $this->num_pages and $this->items_total >= 10) and ($_GET['Page'] != 'All')) ? "<a class=\"paginate\" href=\"" . $this->url_next . $next_page . "\">Next &raquo;</a>\n" : "<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";
        } else {
            for ($i = 1; $i <= $this->num_pages; $i++) {
                $this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" href=\"" . $this->url_next . $i . "\">$i</a> ";
            }
        }
        //$this->low = ($this->current_page - 1) * $this->items_per_page;
        //$this->high = ($_GET['ipp'] == 'All') ? $this->items_total : ($this->current_page * $this->items_per_page) - 1;
        //$this->limit = ($_GET['ipp'] == 'All') ? "" : " LIMIT $this->low,$this->items_per_page";
    }

    function previous()
    {
        $current_page = $this->current_page;
        $prev_page = $this->current_page - 1;
        if ($current_page > 1) {
            echo "<a class='paginate' href='".$this->url_next . $prev_page."'>ก่อนหน้า</a> ";
        } else {
            echo "<a class='current'>ก่อนหน้า</a> ";
        }
    }

    function display_pages()
    {
        return $this->return;
    }
}
?>
<html>

<head>
    <title>ThaiCreate.Com PHP & MySQL Tutorial</title>
</head>
<style type="text/css">
    <!--
    .paginate {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
    }

    a.paginate {
        border: 1px solid #000080;
        padding: 2px 6px 2px 6px;
        text-decoration: none;
        color: #000080;
    }

    h2 {
        font-size: 12pt;
        color: #003366;
    }

    h2 {
        line-height: 1.2em;
        letter-spacing: -1px;
        margin: 0;
        padding: 0;
        text-align: left;
    }

    a.paginate:hover {
        background-color: #000080;
        color: #FFF;
        text-decoration: underline;
    }

    a.current {
        border: 1px solid #000080;
        font: bold .7em Arial, Helvetica, sans-serif;
        padding: 2px 6px 2px 6px;
        cursor: default;
        background: #000080;
        color: #FFF;
        text-decoration: none;
    }

    span.inactive {
        border: 1px solid #999;
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        padding: 2px 6px 2px 6px;
        color: #999;
        cursor: default;
    }
    -->
</style>

<body>
    <?php

    $link = mysqli_connect("localhost", "root", "krittawat", "foodordersystem") or die("Error Connect to Database");
    // $objDB = mysql_select_db("mydatabase");
    $strSQL = "SELECT * FROM customers";
    $objQuery = mysqli_query($link, $strSQL) or die("Error Query [" . $strSQL . "]");
    $Num_Rows = mysqli_num_rows($objQuery);

    $Per_Page = 10;   // Per Page

    $Page = $_GET["Page"];
    if (!$_GET["Page"]) {
        $Page = 1;
    }

    $Prev_Page = $Page - 1;
    $Next_Page = $Page + 1;

    $Page_Start = (($Per_Page * $Page) - $Per_Page);
    if ($Num_Rows <= $Per_Page) {
        $Num_Pages = 1;
    } else if (($Num_Rows % $Per_Page) == 0) {
        $Num_Pages = ($Num_Rows / $Per_Page);
    } else {
        $Num_Pages = ($Num_Rows / $Per_Page) + 1;
        $Num_Pages = (int) $Num_Pages;
    }

    $strSQL .= " order  by cusid ASC LIMIT $Page_Start , $Per_Page";
    $objQuery  = mysqli_query($link, $strSQL);
    ?>
    <table width="600" border="1">
        <tr>
            <th width="91">
                <div align="center">CustomerID </div>
            </th>
            <th width="98">
                <div align="center">Name </div>
            </th>
            <th width="198">
                <div align="center">Email </div>
            </th>
        </tr>
        <?php
        while ($objResult = mysqli_fetch_array($objQuery)) {
        ?>
            <tr>
                <td>
                    <div align="center"><?php echo $objResult["cusid"]; ?></div>
                </td>
                <td><?php echo $objResult["cus_name"]; ?></td>
                <td><?php echo $objResult["cus_email"]; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>

    <br>
    Total <?php echo $Num_Rows; ?> Record

    <?php

    $pages = new Paginator;
    $pages->items_total = $Num_Rows;
    $pages->mid_range = 10;
    $pages->current_page = $Page;
    $pages->default_ipp = $Per_Page;
    $pages->url_next = $_SERVER["PHP_SELF"] . "?QueryString=value&Page=";
    $pages->previous();
    $pages->paginate();

    echo $pages->display_pages();

    ?>


    <?php
    mysqli_close($link);
    ?>
</body>

</html>