async function main() {
    // Dynamically import 'node-fetch'
    const fetch = (await import('node-fetch')).default;

    const { Octokit } = require("@octokit/core");
    const OpenAI = require("openai");
    const fs = require('fs');

    const openai = new OpenAI({
        apiKey: process.env.OPENAI_API_KEY,
    });
    const githubToken = process.env.GITHUB_TOKEN;

    const aiModel = process.env.AI_MODEL || 'gpt-4-1106-preview';
    const maxTokens = parseInt(process.env.MAX_TOKENS || '500');
    const githubRepo = process.env.GITHUB_REPOSITORY;
    let eventPayload = JSON.parse(fs.readFileSync(process.env.GITHUB_EVENT_PATH, 'utf8'));
    const issueNumber = eventPayload.issue.number;

    const octokit = new Octokit({
        auth: githubToken,
        request: { fetch }
    });


    try {
        // Get issue information
        const { data: issue } = await octokit.request(`GET /repos/${githubRepo}/issues/${issueNumber}`);
        const userLogin = issue.user.login;
        const originalIssueText = issue.body;

        // Construct the AI prompt
        const aiPrompt = `Please rephrase and summarize the following issue to make it clearer and provide additional context where necessary:\n${originalIssueText}\n\n` +
                         `Identify the type of this issue. Is it a documentation update, a bug report, a feature request, or something else?\n\n` +
                         `Tag the user @${userLogin} who created this issue and list a few questions that could help clarify the issue for better understanding and resolution.`;

        // OpenAI ChatCompletion request
        const chatCompletion = await openai.chat.completions.create({
            messages: [
                { role: "system", content: "You are a helpful assistant experienced in software development and issue tracking. Your task is to analyze GitHub issues, provide clear and concise summaries, categorize them accurately (e.g., bug, feature request, documentation, …), and generate relevant questions to clarify each issue for better understanding. Your response should be concise and technical, in a markdown format. Please also add a title for each section." },
                { role: "user", content: aiPrompt }
            ],
            model: aiModel,
            max_tokens: maxTokens
        });

        // Update the issue with the response
        await octokit.request(`POST /repos/${githubRepo}/issues/${issueNumber}/comments`, {
            body: `AI-Assisted Response:\n${chatCompletion.choices[0].message['content']}`
        });
    } catch (error) {
        console.error("Error processing issue:", error);
    }
}

main().catch(error => {
    console.error("Error in main function:", error);
    process.exit(1);
});
