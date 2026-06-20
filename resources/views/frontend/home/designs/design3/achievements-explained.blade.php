<link rel="stylesheet" href="{{ asset('assets/frontend/css/design3/design3-achievements-explained.css') }}">

<section class="d3-achievements-explained-section d3-achievements-alqurra">
    <div class="container">
        <!-- Section Header -->
        <div class="d3-section-header">
            <h2 class="d3-section-title">How Achievements Are Calculated</h2>
            <p class="d3-section-subtitle">Understand how your performance determines your title</p>
        </div>

        <!-- Achievement Tiers -->
        <div class="d3-achievements-grid">
            <!-- Champion -->
            <div class="d3-achievement-card d3-achievement-champion d3-achievement-alqurra">
                <div class="d3-achievement-header">
                    <div class="d3-achievement-icon d3-achievement-icon-champion">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3>🏆 Champion</h3>
                </div>

                <div class="d3-achievement-body">
                    <p class="d3-achievement-description">
                        You are a <strong>Champion</strong> when you achieve the highest level of excellence.
                    </p>

                    <div class="d3-achievement-criteria">
                        <h4>Requirements:</h4>
                        <ul>
                            <li><strong>Score ≥ 90%</strong> (Highest Excellence)</li>
                            <li><strong>AND</strong> Score ≥ Your APT</li>
                        </ul>
                    </div>

                    <div class="d3-achievement-formula">
                        <code>Score ≥ 90 AND Score ≥ APT</code>
                    </div>

                    <div class="d3-achievement-examples">
                        <h4>Examples:</h4>
                        <table class="d3-achievement-table">
                            <tr>
                                <td><strong>APT</strong></td>
                                <td><strong>Score</strong></td>
                                <td><strong>Result</strong></td>
                            </tr>
                            <tr>
                                <td>90</td>
                                <td>94</td>
                                <td>🏆 Champion</td>
                            </tr>
                            <tr>
                                <td>92</td>
                                <td>95</td>
                                <td>🏆 Champion</td>
                            </tr>
                            <tr>
                                <td>95</td>
                                <td>91</td>
                                <td>🎓 Scholar</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Scholar -->
            <div class="d3-achievement-card d3-achievement-scholar d3-achievement-alqurra">
                <div class="d3-achievement-header">
                    <div class="d3-achievement-icon d3-achievement-icon-scholar">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>🎓 Scholar</h3>
                </div>

                <div class="d3-achievement-body">
                    <p class="d3-achievement-description">
                        You are a <strong>Scholar</strong> when you perform excellently across the board.
                    </p>

                    <div class="d3-achievement-criteria">
                        <h4>Requirements:</h4>
                        <ul>
                            <li><strong>Score: 80% - 89.99%</strong></li>
                            <li><strong>Regardless of APT</strong></li>
                        </ul>
                    </div>

                    <div class="d3-achievement-formula">
                        <code>80 ≤ Score &lt; 90</code>
                    </div>

                    <div class="d3-achievement-examples">
                        <h4>Examples:</h4>
                        <table class="d3-achievement-table">
                            <tr>
                                <td><strong>APT</strong></td>
                                <td><strong>Score</strong></td>
                                <td><strong>Result</strong></td>
                            </tr>
                            <tr>
                                <td>90</td>
                                <td>88</td>
                                <td>🎓 Scholar</td>
                            </tr>
                            <tr>
                                <td>75</td>
                                <td>84</td>
                                <td>🎓 Scholar</td>
                            </tr>
                            <tr>
                                <td>50</td>
                                <td>85</td>
                                <td>🎓 Scholar</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rising Star -->
            <div class="d3-achievement-card d3-achievement-rising d3-achievement-alqurra">
                <div class="d3-achievement-header">
                    <div class="d3-achievement-icon d3-achievement-icon-rising">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>⭐ Rising Star</h3>
                </div>

                <div class="d3-achievement-body">
                    <p class="d3-achievement-description">
                        You are a <strong>Rising Star</strong> when you meet your personal target.
                    </p>

                    <div class="d3-achievement-criteria">
                        <h4>Requirements:</h4>
                        <ul>
                            <li><strong>Score ≥ Your APT</strong></li>
                            <li><strong>AND</strong> Score &lt; 80%</li>
                        </ul>
                    </div>

                    <div class="d3-achievement-formula">
                        <code>Score ≥ APT AND Score &lt; 80</code>
                    </div>

                    <div class="d3-achievement-examples">
                        <h4>Examples:</h4>
                        <table class="d3-achievement-table">
                            <tr>
                                <td><strong>APT</strong></td>
                                <td><strong>Score</strong></td>
                                <td><strong>Result</strong></td>
                            </tr>
                            <tr>
                                <td>60</td>
                                <td>65</td>
                                <td>⭐ Rising Star</td>
                            </tr>
                            <tr>
                                <td>45</td>
                                <td>52</td>
                                <td>⭐ Rising Star</td>
                            </tr>
                            <tr>
                                <td>70</td>
                                <td>75</td>
                                <td>⭐ Rising Star</td>
                            </tr>
                            <tr>
                                <td>78</td>
                                <td>81</td>
                                <td>🎓 Scholar</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Priority Order -->
        <div class="d3-priority-box d3-priority-alqurra">
            <h3>🎯 Evaluation Priority</h3>
            <p>When determining your achievement title, we evaluate in this order:</p>
            <div class="d3-priority-flow">
                <div class="d3-priority-step">
                    <div class="d3-priority-number">1</div>
                    <p><strong>Score ≥ 90 AND Score ≥ APT?</strong><br/><span class="d3-priority-result">→ Champion</span></p>
                </div>
                <div class="d3-priority-arrow">→</div>
                <div class="d3-priority-step">
                    <div class="d3-priority-number">2</div>
                    <p><strong>Score ≥ 80?</strong><br/><span class="d3-priority-result">→ Scholar</span></p>
                </div>
                <div class="d3-priority-arrow">→</div>
                <div class="d3-priority-step">
                    <div class="d3-priority-number">3</div>
                    <p><strong>Score ≥ APT?</strong><br/><span class="d3-priority-result">→ Rising Star</span></p>
                </div>
            </div>
        </div>

        <!-- APT Explanation -->
        <div class="d3-apt-box d3-apt-alqurra">
            <div class="d3-apt-content">
                <div class="d3-apt-icon">
                    <i class="fas fa-target"></i>
                </div>
                <div class="d3-apt-text">
                    <h4>What is APT?</h4>
                    <p><strong>Academic Performance Target (APT)</strong> is your personal target score based on your class and academic level. Your APT determines how well you need to perform to achieve the Rising Star and Champion titles.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Animate cards when they come into view
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.d3-achievement-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('d3-animate-in');
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(card => observer.observe(card));
    });
</script>
