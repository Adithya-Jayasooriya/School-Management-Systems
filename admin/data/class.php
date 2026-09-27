<?php 
// All classes
function getAllClasses($conn){
   $sql = "SELECT * FROM classes"; // ✅ corrected table name
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
     return $classes;
   }else {
    return 0;
   }
}

// Get class by ID
function getClassById($class_id, $conn){
   $sql = "SELECT * FROM classes
           WHERE class_id=?"; // ✅ corrected table name
   $stmt = $conn->prepare($sql);
   $stmt->execute([$class_id]);

   if ($stmt->rowCount() == 1) {
     $class = $stmt->fetch(PDO::FETCH_ASSOC);
     return $class;
   }else {
    return 0;
   }
}

// DELETE
function removeClass($id, $conn){
   $sql  = "DELETE FROM classes
           WHERE class_id=?"; // ✅ corrected table name
   $stmt = $conn->prepare($sql);
   $re   = $stmt->execute([$id]);
   if ($re) {
     return 1;
   }else {
    return 0;
   }
}
?>