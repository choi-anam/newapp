<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailConfig;
use Illuminate\Http\Request;

class EmailConfigController extends Controller
{
    /**
     * Display email configuration page
     */
    public function index()
    {
        $service = app(\App\Services\EmailConfigService::class);
        $configs = EmailConfig::all();
        
        // Get from config if no database records
        if ($configs->isEmpty()) {
            $configs = collect([
               $service->getActiveEmailConfig(), // Fallback ENV
            ]);
        }

        $mailers = ['smtp', 'sendmail', 'log', 'array', 'mailgun', 'postmark', 'ses'];
        $encryptions = ['tls', 'ssl', null];

        return view('admin.settings.email-config', compact('configs', 'mailers', 'encryptions'));
    }

    /**
     * Store email configuration
     */
    public function store(Request $request)
    {
        $request->validate([
            'mailer' => 'required|in:smtp,sendmail,log,array,mailgun,postmark,ses',
            'host' => 'nullable|string',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'required|string|max:255',
            'is_enabled' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        EmailConfig::create([
            'mailer' => $request->input('mailer'),
            'host' => $request->input('host'),
            'port' => $request->input('port'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'encryption' => $request->input('encryption'),
            'from_address' => $request->input('from_address'),
            'from_name' => $request->input('from_name'),
            'is_enabled' => $request->has('is_enabled'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.settings.email-config.index')
            ->with('success', 'Email configuration added successfully.');
    }

    /**
     * Update email configuration
     */
    public function update(Request $request, EmailConfig $config)
    {
        $request->validate([
            'mailer' => 'required|in:smtp,sendmail,log,array,mailgun,postmark,ses',
            'host' => 'nullable|string',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'required|string|max:255',
            'is_enabled' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        $data = [
            'mailer' => $request->input('mailer'),
            'host' => $request->input('host'),
            'port' => $request->input('port'),
            'username' => $request->input('username'),
            'encryption' => $request->input('encryption'),
            'from_address' => $request->input('from_address'),
            'from_name' => $request->input('from_name'),
            'is_enabled' => $request->has('is_enabled'),
            'description' => $request->input('description'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $config->update($data);

        return redirect()->route('admin.settings.email-config.index')
            ->with('success', 'Email configuration updated successfully.');
    }

    /**
     * Delete email configuration
     */
    public function destroy(EmailConfig $config)
    {
        $config->delete();

        return redirect()->route('admin.settings.email-config.index')
            ->with('success', 'Email configuration deleted successfully.');
    }

    /**
     * Toggle configuration status via AJAX
     */
    public function toggle(EmailConfig $config)
    {
        $config->update(['is_enabled' => ! $config->is_enabled]);

        return response()->json([
            'success' => true,
            'is_enabled' => $config->is_enabled,
        ]);
    }

    /**
     * Test email configuration
     */
    public function test(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'config_id' => 'nullable|exists:email_configs,id',
        ]);

        try {
            // get service
            $service = app(\App\Services\EmailConfigService::class);

            // get config (DB atau fallback ENV)
            $emailConfig = $service->getActiveEmailConfig($request->config_id);
            
            // Apply runtime mail config
            $service->applyMailConfig($emailConfig);
            
            // Send test email
            \Illuminate\Support\Facades\Mail::raw('This is a test email from ' . config('app.name') . '. Your email configuration is working correctly!', function ($message) use ($request, $emailConfig) {
               $message->to($request->input('email'))
                  ->subject('Test Email - ' . config('app.name'))
                  ->from($emailConfig->from_address, $emailConfig->from_name);
            });
            
            return redirect()->back()
               ->with('success', 'Test email sent successfully to ' . $request->input('email'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error testing email configuration: ' . $e->getMessage());
        }
    }
}
