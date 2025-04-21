<footer class="full-row bg-secondary p-0">
    <div class="container">
        <div  class="row">
            <div class="col-lg-12">
                <div class="divider py-40">
                    <div class="row">
                        <div class="col-md-12 col-lg-4">
                            <div class="footer-widget mb-4">
                                <div class="footer-logo mb-4"> 
                                    <a href="#"><img class="logo-bottom" src="images/logo/restatelg_white.png" alt="image"></a> 
                                </div>
                                <p class="pb-20 text-white">
                                    The Real Estate Web Project is a dynamic platform designed to simplify property listings, searches, and transactions.
                                    It allows buyers and sellers to connect, communicate via chat, and manage listings efficiently.
                                    Admins can monitor activities for security and moderation.
                                    The system ensures a seamless user experience with features like search filters, chat history, and real-time updates.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-8">
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="footer-widget footer-nav mb-4">
                                        <h4 class="widget-title text-white double-down-line-left position-relative">Support</h4>
                                        <ul class="hover-text-primary">
                                            <li><a href="#" class="text-white">Forum</a></li>
                                            <li><a href="#" class="text-white">Terms and Condition</a></li>
                                            <li><a href="#" class="text-white">Frequently Asked Question</a></li>
                                            <li><a href="contact.php" class="text-white">Contact</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="footer-widget footer-nav mb-4">
                                        <h4 class="widget-title text-white double-down-line-left position-relative">Quick Links</h4>
                                        <ul class="hover-text-primary">
                                            <li><a href="about.php" class="text-white">About Us</a></li>
                                            <li><a href="#" class="text-white">Featured Property</a></li>
                                            <li><a href="#" class="text-white">Submit Property</a></li>
                                            <li><a href="agent.php" class="text-white">Our Agents</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="footer-widget">
                                        <h4 class="widget-title text-white double-down-line-left position-relative">Contact Us</h4>
                                        <ul class="text-white">
                                            <li class="hover-text-primary"><i class="fas fa-map-marker-alt text-white mr-2 font-13 mt-1"></i>27 Ingram Street, Dayton</li>
                                            <li class="hover-text-primary"><i class="fas fa-phone-alt text-white mr-2 font-13 mt-1"></i>+1 234-567-8910</li>
                                            <li class="hover-text-primary"><i class="fas fa-phone-alt text-white mr-2 font-13 mt-1"></i>+1 243-765-4321</li>
                                            <li class="hover-text-primary"><i class="fas fa-envelope text-white mr-2 font-13 mt-1"></i>helpline@realestatest.com</li>
                                        </ul>
                                    </div>
                                    <div class="footer-widget media-widget mt-4 text-white hover-text-primary">
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                        <a href="#"><i class="fab fa-google-plus-g"></i></a>
                                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="#"><i class="fas fa-rss"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row copyright">
            <div class="col-sm-6">
                <span class="text-white">© <?php echo date('Y');?> Real Estate Website - DBMT_GROUP1</span>
            </div>
            <div class="col-sm-6">
                <ul class="line-menu text-white hover-text-primary float-right">
                    <li><a href="#">Privacy & Policy</a></li>
                    <li>|</li>
                    <li><a href="#">Site Map</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 🔹 Chatbot UI Starts Here -->
    <style>
        #chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 9999;
            font-family: 'Muli', sans-serif;
        }
        #chat-header {
            background: #28a745;
            color: white;
            padding: 10px;
            cursor: pointer;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
        }
        #chat-body {
            max-height: 250px;
            overflow-y: auto;
            padding: 10px;
            display: none;
            font-size: 14px;
        }
        #chat-input {
            display: none;
            border-top: 1px solid #ddd;
        }
        #chat-input input {
            width: 75%;
            padding: 10px;
            border: none;
            outline: none;
        }
        #chat-input button {
            width: 25%;
            background: #28a745;
            border: none;
            color: white;
            padding: 10px;
        }
    </style>

    <div id="chat-container">
        <div id="chat-header" onclick="toggleChat()">💬 Need Help?</div>
        <div id="chat-body"></div>
        <div id="chat-input">
            <input type="text" id="user-msg" placeholder="Ask something...">
            <button onclick="sendChat()">Send</button>
        </div>
    </div>

    <script>
        function toggleChat() {
            const body = document.getElementById("chat-body");
            const input = document.getElementById("chat-input");
            const show = body.style.display !== "block";
            body.style.display = input.style.display = show ? "block" : "none";
        }

        function sendChat() {
            let msg = document.getElementById("user-msg").value.trim();
            if (!msg) return;

            const chatBody = document.getElementById("chat-body");
            chatBody.innerHTML += `<div><b>You:</b> ${msg}</div>`;

            fetch("chatbot.php", {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `msg=${encodeURIComponent(msg)}`
            })
            .then(res => res.text())
            .then(reply => {
                chatBody.innerHTML += `<div><b>Bot:</b> ${reply}</div>`;
                chatBody.scrollTop = chatBody.scrollHeight;
            });

            document.getElementById("user-msg").value = "";
        }
    </script>
    <!-- 🔹 Chatbot UI Ends Here -->

</footer>
