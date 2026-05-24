<?php
echo "<div style='font-family:Arial; padding: 20px;'>";
echo "<h3>PHP Array Operations</h3>";
$array1 = array(15, 42, 8, 23);
$array2 = array(10, 5, 99);
echo "<b>Original Array 1:</b> " . implode(", ", $array1) . "<br>";
echo "<b>Original Array 2:</b> " . implode(", ", $array2) . "<br><br>";
$merged_array = array_merge($array1, $array2);
echo "<b>Merged Array:</b> " . implode(", ", $merged_array) . "<br><br>";
sort($merged_array);
echo "<b>Sorted Merged Array (Ascending):</b> " . implode(", ", $merged_array) . "<br><br>";
$search_value = 23;
$index = array_search($search_value, $merged_array);
if ($index !== false) {
    echo "<span style='color:green;'><b>Search Result:</b> Value {$search_value} was found at index {$index} (0-based) in the sorted array.</span><br>";
}
echo "</div>";
?>