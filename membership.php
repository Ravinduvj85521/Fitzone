<?php 
require __DIR__ . '/includes/config.php';
$pageTitle = "Membership Plans";
$customCSS = "membership.css";

include 'includes/header.php'; 
?>

<section class="membership-section">
    <h1>Membership Plans</h1>
    <p class="subtitle">Choose the perfect plan for your fitness journey</p>
    
    <div class="plans-grid">
        <div class="plan-card">
            <div class="plan-header">
                <h3>Basic</h3>
                <p class="price">Rs. 3,000<span>/month</span></p>
            </div>
            <div class="plan-features">
                <ul>
                    <li>Access to gym equipment</li>
                    <li>Open gym hours</li>
                    <li>1 free class per week</li>
                    <li>Locker room access</li>
                    <li>Free Wi-Fi</li>
                </ul>
            </div>
            <div class="plan-footer">
                <a href="register.php?plan=basic" class="join-btn">Join Now</a>
            </div>
        </div>
        
        <div class="plan-card featured">
            <div class="plan-header">
                <h3>Premium</h3>
                <p class="price">Rs. 5,000<span>/month</span></p>
            </div>
            <div class="plan-features">
                <ul>
                    <li>All Basic benefits</li>
                    <li>Unlimited classes</li>
                    <li>2 personal training sessions</li>
                    <li>Nutrition consultation</li>
                    <li>Progress tracking</li>
                </ul>
            </div>
            <div class="plan-footer">
                <a href="register.php?plan=premium" class="join-btn">Get Premium</a>
            </div>
        </div>
        
        <div class="plan-card">
            <div class="plan-header">
                <h3>VIP</h3>
                <p class="price">Rs. 8,000<span>/month</span></p>
            </div>
            <div class="plan-features">
                <ul>
                    <li>All Premium benefits</li>
                    <li>Unlimited personal training</li>
                    <li>24/7 access</li>
                    <li>Monthly body analysis</li>
                    <li>VIP locker</li>
                </ul>
            </div>
            <div class="plan-footer">
                <a href="register.php?plan=vip" class="join-btn">Go VIP</a>
            </div>
        </div>
    </div>
    
<div class="comparison-section">
    <h2>Plan Comparison</h2>
    <table class="comparison-table">
        <thead>
            <tr>
                <th>Feature</th>
                <th>Basic</th>
                <th>Premium</th>
                <th>VIP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gym Access</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
            </tr>
            <tr>
                <td>Access Hours</td>
                <td>Standard Hours</td>
                <td>Standard Hours</td>
                <td class="check-mark">24/7 Access</td>
            </tr>
            <tr>
                <td>Group Classes</td>
                <td>1 per week</td>
                <td class="check-mark">Unlimited</td>
                <td class="check-mark">Unlimited</td>
            </tr>
            <tr>
                <td>Personal Training</td>
                <td class="cross-mark">✗</td>
                <td>2 sessions/month</td>
                <td class="check-mark">Unlimited</td>
            </tr>
            <tr>
                <td>Locker Access</td>
                <td>Shared</td>
                <td>Shared</td>
                <td class="check-mark">VIP Locker</td>
            </tr>
            <tr>
                <td>Nutrition Consultation</td>
                <td class="cross-mark">✗</td>
                <td>1 per month</td>
                <td>2 per month</td>
            </tr>
            <tr>
                <td>Body Analysis</td>
                <td class="cross-mark">✗</td>
                <td class="cross-mark">✗</td>
                <td class="check-mark">Monthly</td>
            </tr>
            <tr>
                <td>Progress Tracking</td>
                <td class="cross-mark">✗</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
            </tr>
            <tr>
                <td>Guest Passes</td>
                <td class="cross-mark">✗</td>
                <td>1 per month</td>
                <td>2 per month</td>
            </tr>
            <tr>
                <td>Towels Service</td>
                <td class="cross-mark">✗</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
            </tr>
            <tr>
                <td>Sauna/Steam Room</td>
                <td class="cross-mark">✗</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
            </tr>
            <tr>
                <td>Priority Booking</td>
                <td class="cross-mark">✗</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
            </tr>
            <tr>
                <td>Online Workouts</td>
                <td class="cross-mark">✗</td>
                <td class="check-mark">✓</td>
                <td class="check-mark">✓</td>
            </tr>
            <tr>
                <td>Discounts on Merchandise</td>
                <td>5%</td>
                <td>10%</td>
                <td>15%</td>
            </tr>
            <tr>
                <td>Freeze Membership</td>
                <td class="cross-mark">✗</td>
                <td>7 days/year</td>
                <td>14 days/year</td>
            </tr>
        </tbody>
    </table>
</div>
    
    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <div class="faq-question">Can I upgrade my plan later?</div>
            <div class="faq-answer">
                <p>Yes! You can upgrade your plan at any time. The difference in price will be prorated based on when you upgrade.</p>
            </div>
        </div>
        <!-- More FAQ items -->
    </div>
</section>

<script>
// FAQ toggle functionality
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        question.classList.toggle('active');
        answer.classList.toggle('show');
    });
});
</script>

<?php include 'includes/footer.php'; ?>