<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \HubSpot;

class HubspotMailchimp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubspot:mailchimp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe HubSpot contacts to MailChimp newsletter';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Started: fetching all HubSpot contacts' );

        $response = $this->getContacts();

        $contacts = [];
        $invalids = [];
        while ($response->{'has-more'}) {

            foreach ($response->contacts as $contact) {

                $firstName = isset($contact->properties->firstname) ?  $contact->properties->firstname->value : '';
                $lastName = isset($contact->properties->lastname) ? $contact->properties->lastname->value : '';

                $email = null;

                //Get the e-mail
                foreach($contact->{'identity-profiles'}[0]->{'identities'} as $identity){
                    if($identity->{'type'} == 'EMAIL'){
                        $email = $identity->{'value'};
                    }
                }

                if($email)
                {
                    $contacts [] = ['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email];
                }

                if(!$email){
                    $invalids[] = ['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email];
                }
            }

            $response = $this->getContacts($response->{'vid-offset'});
        }

        if(!$response->{'has-more'}){
            foreach ($response->contacts as $contact) {

                $firstName = isset($contact->properties->firstname) ?  $contact->properties->firstname->value : '';
                $lastName = isset($contact->properties->lastname) ? $contact->properties->lastname->value : '';

                //Get the e-mail
                foreach($contact->{'identity-profiles'}[0]->{'identities'} as $identity){
                    if($identity->{'type'} == 'EMAIL'){
                        $email = $identity->{'value'};
                    }
                }

                if($email)
                {
                    $contacts [] = ['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email];
                }

                if(!$email){
                    $invalids[] = ['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email];
                }
            }
        }

        $this->info('Done: fetched ' .count($contacts) . ' contacts');

        $this->warn('Starting MailChimp integration (this operation may take awhile)');

        $bar = $this->output->createProgressBar(count($contacts));

        $already_subscribed = [];
        $subscribed = [];
        foreach ($contacts as $contact) {
            $is_subscribed = \Newsletter::isSubscribed($contact['email']);

            if($is_subscribed){
                $already_subscribed[] = $contact;
            }

            if(!$is_subscribed){

                //Subscribe
                if($contact['email']){

                   \Newsletter::subscribe($contact['email'], ['FNAME' => $contact['firstName'], 'LNAME' => $contact['lastName']], 'isaudavel_professionals');
                }

                $subscribed[] = $contact;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info('');
        $this->info('--------------------------------------------------');
        $this->info('Contacts processed: ' . count($contacts));
        $this->info('Already subscribed: ' . count($already_subscribed));
        $this->info('New subscriptions: ' . count($subscribed));
        $this->info('Invalid contacts: ' . count($invalids));
    }

    /**
     * Get hubspot contacts baset on offset
     * @param int $offset
     * @return mixed
     */
    public function getContacts($offset = 0){
        return  HubSpot::contacts()->all(['count' => 100, 'vidOffset' => $offset])->data;
    }
}
