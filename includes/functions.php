<?php
function getUpcomingClasses($limit = 3) {
    global $conn;
    
    try {
        $query = "SELECT c.class_id, c.class_name, c.start_time, c.end_time, 
                  t.name AS trainer_name, c.class_type, 
                  (c.max_capacity - c.current_bookings) AS available_spots
                  FROM classes c
                  JOIN trainers t ON c.trainer_id = t.trainer_id
                  WHERE c.start_time > NOW() 
                  ORDER BY c.start_time LIMIT ?";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Error in getUpcomingClasses: " . $e->getMessage());
        return false;
    }
}
function getTrainers($specialization = null) {
    global $conn;
    
    try {
        $query = "SELECT * FROM trainers";
        if ($specialization) {
            $query .= " WHERE specialization = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $specialization);
        } else {
            $stmt = $conn->prepare($query);
        }
        
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Error in getTrainers: " . $e->getMessage());
        return false;
    }
}
function validatePassword($password) {
    // Minimum 8 chars, at least 1 uppercase, 1 lowercase, 1 number
    return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W]{8,}$/", $password);
}

function sendNotification($email, $subject, $message) {
    try {
        $headers = "From: FitZone <no-reply@fitzone.lk>\r\n";
        $headers .= "Reply-To: support@fitzone.lk\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Log email sending attempt
        error_log("Attempting to send email to: $email");
        
        $sent = mail($email, $subject, $message, $headers);
        
        if (!$sent) {
            error_log("Failed to send email to: $email");
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if member is booked in a class
 */
function isMemberBooked($member_id, $class_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT booking_id FROM bookings 
                               WHERE member_id = ? AND class_id = ? AND status = 'confirmed'");
        $stmt->bind_param("ii", $member_id, $class_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    } catch (Exception $e) {
        error_log("Error in isMemberBooked: " . $e->getMessage());
        return false;
    }
}

/**
 * Get member's upcoming booked classes
 */
function getMemberBookings($member_id, $limit = 5) {
    global $conn;
    
    try {
        $query = "SELECT c.class_name, c.start_time, b.booking_date 
                  FROM bookings b
                  JOIN classes c ON b.class_id = c.class_id
                  WHERE b.member_id = ? AND c.start_time > NOW()
                  ORDER BY c.start_time LIMIT ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $member_id, $limit);
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Error in getMemberBookings: " . $e->getMessage());
        return false;
    }
}

/**
 * Format date for display
 */
function formatDate($dateString, $format = 'F j, Y g:i a') {
    try {
        $date = new DateTime($dateString);
        return $date->format($format);
    } catch (Exception $e) {
        error_log("Date formatting error: " . $e->getMessage());
        return $dateString; // Return original if formatting fails
    }
}

/**
 * Get membership details
 */
function getMembershipDetails($type) {
    $memberships = [
        'basic' => [
            'price' => 3000,
            'features' => ['Gym access', '1 class/week', 'Locker room']
        ],
        'premium' => [
            'price' => 5000,
            'features' => ['Unlimited classes', '2 PT sessions', 'Nutrition plan']
        ],
        'vip' => [
            'price' => 8000,
            'features' => ['24/7 access', 'Unlimited PT', 'VIP locker']
        ]
    ];
    
    return $memberships[strtolower($type)] ?? null;
}