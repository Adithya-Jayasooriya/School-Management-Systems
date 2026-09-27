<?php 

// All Subjects
function getAllSubjects($conn){
   $sql = "SELECT * FROM subjects";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   // Always return an array, even if empty
   $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
   return $subjects;
}

// Get Subject by ID
function getSubjectById($subject_id, $conn){
   $sql = "SELECT * FROM subjects WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$subject_id]);

   $subject = $stmt->fetch(PDO::FETCH_ASSOC);
   return $subject ?: null; // Return null if not found
}

// DELETE subject
function removeSubject($id, $conn){
   $sql  = "DELETE FROM subjects WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $re   = $stmt->execute([$id]);
   return $re ? true : false;
}

?>