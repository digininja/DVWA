<div class="body_padded">
	<h1>Help - AI Assistant (IA)</h1>

	<p><strong>Note:</strong> the hints below are suggestions of things to try — they may
	or may not work depending on the model and its mood. Model behaviour changes over time, so feel free
	to experiment and adapt them.</p>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>This module exposes an AI assistant ("HR-Bot") that poses as a Human Resources assistant.
			The bot is backed by an LLM (configurable in <code>config/config.inc.php</code>) and its
			"system prompt" is built from an employee salary payroll (the <code>payroll</code> table
			in the database).</p>

		<p>The vulnerability demonstrated is <strong>prompt injection</strong> and <strong>sensitive information disclosure</strong>
			through an LLM (OWASP LLM01 / LLM06). When the prompt guardrail is weak, an attacker can make the model leak
			confidential data such as salaries, bank accounts and national IDs.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your goal is to make the assistant reveal an employee's payslip (for example, the salary of
			<em>Pablo Picasso</em>) or the company's full payroll.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The system prompt is naive: the whole payroll is handed to the model and no restriction is placed on it.
			Simply asking directly for the data is enough for the bot to reveal it.</p>
		<p><em>Hint:</em> there are no restrictions at all — just ask.</p>

		<br />

		<h3>Medium Level</h3>
		<p>A single rule in the system prompt is the only control: the model is told not to share other employees'
			salary, bank account or national ID, but the full payroll is loaded into its context and nothing else
			enforces the rule. A direct question will probably be refused, but an indirect request or a prompt injection
			is likely to get past it.</p>
		<p><em>Hint:</em> the rule is just text in the prompt, with no priority over your message. Think about asking for
			the data in an indirect way, or about making your message look like part of the instructions.</p>

		<br />

		<h3>High Level</h3>
		<p>The full payroll is loaded into the model context, just like Low and Medium. The defense is a hardened
			system prompt with strict refusal rules, plus two filters in code: one rejects messages that contain
			well-known injection phrases, and the other blocks any reply that contains another employee's salary,
			bank account or national ID. The model knows who the logged-in employee is and is told to only share that
			employee's own data, but the entire company's payroll is still in the context.
			How hard the prompt rules are to break depends heavily on the model: larger models follow them much more
			closely, while smaller ones are easier to trick.</p>
		<p><em>Hint:</em> both filters only look for patterns they already know. Think about what they cannot recognise:
			a request that does not use the phrases they watch for, or an answer written in a form they do not
			expect.</p>

		<br />

		<h3>Impossible Level</h3>
		<p>Least privilege is applied in the data layer: only the logged-in employee's own record is loaded into the model
			context (filtered by username in a prepared SQL query, resolved from the session). The rest of the company's
			payroll never leaves the database. You cannot steal what the model never received.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://genai.owasp.org/llmrisk/llm01-prompt-injection/' ); ?></p>
</div>
