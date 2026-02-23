<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'   => 'FAQ',
                'slug'    => 'faq',
                'content' => <<<HTML
<h1>Frequently Asked Questions</h1>

<h2>Ordering</h2>

<h3>How do I place an order?</h3>
<p>Browse our shop, add items to your cart, and proceed to checkout. You'll receive an order confirmation email once your order is placed successfully.</p>

<h3>Can I modify or cancel my order?</h3>
<p>Orders can be modified or cancelled within <strong>2 hours</strong> of placement. After that, they enter our fulfilment process and cannot be changed. Please contact us at <a href="mailto:support@zamzam.com">support@zamzam.com</a> as soon as possible.</p>

<h3>What payment methods do you accept?</h3>
<p>We accept the following payment methods:</p>
<ul>
  <li>Credit &amp; Debit Cards (Visa, MasterCard, Amex)</li>
  <li>Bank Transfer</li>
  <li>Cash on Delivery (selected areas)</li>
</ul>

<h2>Shipping</h2>

<h3>How long will my order take to arrive?</h3>
<p>Standard delivery takes <strong>3–7 business days</strong>. Express delivery options are available at checkout and typically arrive within <strong>1–2 business days</strong>.</p>

<h3>Do you ship internationally?</h3>
<p>Yes, we ship to most countries. International delivery times vary by destination. Customs and duties may apply and are the responsibility of the recipient.</p>

<h2>Returns &amp; Refunds</h2>

<h3>What is your return policy?</h3>
<p>We accept returns within <strong>30 days</strong> of delivery for unused items in their original packaging. Please visit our <a href="/returns">Returns Policy</a> page for full details.</p>

<h3>When will I receive my refund?</h3>
<p>Refunds are processed within <strong>5–10 business days</strong> after we receive and inspect the returned item. The refund will be credited to your original payment method.</p>

<h2>Products</h2>

<h3>Are your products authentic?</h3>
<p>Absolutely. We source all products directly from verified manufacturers and authorised distributors. Every item comes with a quality guarantee.</p>

<h3>How do I know if a product is in stock?</h3>
<p>Stock availability is shown in real time on each product page. If an item is out of stock, you can sign up for a restock notification.</p>
HTML,
            ],

            [
                'title'   => 'Shipping',
                'slug'    => 'shipping',
                'content' => <<<HTML
<h1>Shipping Information</h1>
<p>We strive to deliver your orders as quickly and safely as possible. Below you'll find everything you need to know about our shipping policies.</p>

<h2>Shipping Methods &amp; Delivery Times</h2>

<table>
  <thead>
    <tr>
      <th>Method</th>
      <th>Estimated Delivery</th>
      <th>Cost</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Standard Shipping</td>
      <td>3–7 Business Days</td>
      <td>Calculated at checkout</td>
    </tr>
    <tr>
      <td>Express Shipping</td>
      <td>1–2 Business Days</td>
      <td>Calculated at checkout</td>
    </tr>
    <tr>
      <td>Free Shipping</td>
      <td>5–10 Business Days</td>
      <td>Free on orders over $99</td>
    </tr>
  </tbody>
</table>

<h2>Order Processing</h2>
<p>All orders are processed within <strong>1–2 business days</strong> (excluding weekends and public holidays). You will receive a shipping confirmation email with a tracking number once your order has been dispatched.</p>

<h2>Tracking Your Order</h2>
<p>Once your order ships, you'll receive a tracking link via email. You can also track your order from your account dashboard under <strong>My Orders</strong>.</p>

<h2>International Shipping</h2>
<p>We ship to over <strong>50 countries</strong> worldwide. International orders may be subject to customs duties and import taxes, which are the sole responsibility of the recipient. We are not responsible for delays caused by customs clearance.</p>

<h2>Damaged or Lost Packages</h2>
<p>If your package arrives damaged or does not arrive within the estimated timeframe, please contact us at <a href="mailto:support@zamzam.com">support@zamzam.com</a> within <strong>14 days</strong> of the expected delivery date. We'll work with the carrier to resolve the issue promptly.</p>

<h2>Contact Us</h2>
<p>Have questions about your shipment? We're here to help.</p>
<ul>
  <li><strong>Email:</strong> <a href="mailto:support@zamzam.com">support@zamzam.com</a></li>
  <li><strong>Phone:</strong> +1 (800) 000-0000</li>
  <li><strong>Hours:</strong> Monday–Friday, 9am–5pm</li>
</ul>
HTML,
            ],

            [
                'title'   => 'Returns',
                'slug'    => 'returns',
                'content' => <<<HTML
<h1>Returns &amp; Refund Policy</h1>
<p>We want you to be completely satisfied with your purchase. If you're not happy, we'll make it right. Please read our returns policy carefully.</p>

<h2>Return Eligibility</h2>
<p>Items are eligible for return if they meet <strong>all</strong> of the following conditions:</p>
<ul>
  <li>Returned within <strong>30 days</strong> of the delivery date.</li>
  <li>Unused, unworn, and in their original condition.</li>
  <li>In the original packaging with all tags and accessories included.</li>
  <li>Accompanied by proof of purchase (order number or receipt).</li>
</ul>

<h2>Non-Returnable Items</h2>
<p>The following items <strong>cannot be returned</strong>:</p>
<ul>
  <li>Perishable goods (food, flowers, etc.)</li>
  <li>Downloadable digital products</li>
  <li>Gift cards</li>
  <li>Items marked as <em>Final Sale</em></li>
  <li>Hazardous materials</li>
</ul>

<h2>How to Initiate a Return</h2>
<ol>
  <li>Email us at <a href="mailto:returns@zamzam.com">returns@zamzam.com</a> with your order number and reason for return.</li>
  <li>We'll send you a <strong>Return Merchandise Authorisation (RMA)</strong> number and return instructions within 48 hours.</li>
  <li>Pack the item securely and include your RMA number on the outside of the package.</li>
  <li>Ship the item to the address we provide. Return shipping costs are the customer's responsibility unless the item is defective or incorrectly sent.</li>
</ol>

<h2>Refunds</h2>
<p>Once we receive and inspect your return, we will notify you by email. Approved refunds are processed within <strong>5–10 business days</strong> and will be credited to your original payment method. Please note that it may take additional time for your bank or card provider to post the credit.</p>

<h2>Exchanges</h2>
<p>We offer exchanges for defective or damaged items only. If you received a defective item, contact us at <a href="mailto:support@zamzam.com">support@zamzam.com</a> within <strong>7 days</strong> of delivery.</p>

<h2>Questions?</h2>
<p>If you have any questions about our returns process, please don't hesitate to reach out to our support team at <a href="mailto:support@zamzam.com">support@zamzam.com</a>.</p>
HTML,
            ],

            [
                'title'   => 'Privacy Policy',
                'slug'    => 'privacy-policy',
                'content' => <<<HTML
<h1>Privacy Policy</h1>
<p><em>Last updated: February 23, 2026</em></p>
<p>Zam Zam Import &amp; Export Inc ("<strong>we</strong>", "<strong>us</strong>", or "<strong>our</strong>") is committed to protecting your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or make a purchase.</p>

<h2>1. Information We Collect</h2>
<p>We may collect the following types of information:</p>
<ul>
  <li><strong>Personal Identification:</strong> Name, email address, phone number, billing and shipping address.</li>
  <li><strong>Payment Information:</strong> Credit card details (processed securely — we do not store full card numbers).</li>
  <li><strong>Technical Data:</strong> IP address, browser type, operating system, referring URLs, and pages visited.</li>
  <li><strong>Usage Data:</strong> Products viewed, cart activity, and purchase history.</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use the information we collect to:</p>
<ul>
  <li>Process and fulfil your orders.</li>
  <li>Send transactional emails (order confirmations, shipping updates).</li>
  <li>Respond to customer service requests.</li>
  <li>Improve our website, products, and services.</li>
  <li>Send promotional communications (with your consent).</li>
  <li>Comply with legal obligations.</li>
</ul>

<h2>3. Sharing Your Information</h2>
<p>We do <strong>not</strong> sell, trade, or rent your personal information to third parties. We may share your information with:</p>
<ul>
  <li><strong>Service Providers:</strong> Payment processors, shipping carriers, and email service providers who help us operate our business.</li>
  <li><strong>Legal Authorities:</strong> When required by law, court order, or government regulation.</li>
</ul>

<h2>4. Cookies</h2>
<p>We use cookies and similar tracking technologies to enhance your experience on our site. You can control cookie settings through your browser preferences. Disabling cookies may affect some functionality.</p>

<h2>5. Data Security</h2>
<p>We implement industry-standard security measures including SSL encryption to protect your personal data during transmission. However, no method of electronic storage or transmission is 100% secure.</p>

<h2>6. Your Rights</h2>
<p>Depending on your location, you may have the right to:</p>
<ul>
  <li>Access the personal data we hold about you.</li>
  <li>Request correction of inaccurate data.</li>
  <li>Request deletion of your data.</li>
  <li>Opt out of marketing communications at any time.</li>
</ul>
<p>To exercise any of these rights, please contact us at <a href="mailto:privacy@zamzam.com">privacy@zamzam.com</a>.</p>

<h2>7. Third-Party Links</h2>
<p>Our website may contain links to third-party sites. We are not responsible for the privacy practices of those sites and encourage you to review their privacy policies.</p>

<h2>8. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. The updated version will be posted on this page with a revised "Last updated" date. Continued use of our website after changes are made constitutes acceptance of the updated policy.</p>

<h2>9. Contact Us</h2>
<p>If you have questions or concerns about this Privacy Policy, please contact us:</p>
<ul>
  <li><strong>Email:</strong> <a href="mailto:privacy@zamzam.com">privacy@zamzam.com</a></li>
  <li><strong>Address:</strong> Zam Zam Import &amp; Export Inc, 123 Commerce Street, Dhaka, Bangladesh</li>
</ul>
HTML,
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
