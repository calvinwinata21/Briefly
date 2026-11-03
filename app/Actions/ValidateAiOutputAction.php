<?php

namespace App\Actions;

class ValidateAiOutputAction
{
    /**
     * Validate and sanitize AI Output against the formal JSON schema contract.
     *
     * @return array ['is_valid' => bool, 'errors' => array, 'data' => array]
     */
    public function execute(array $data): array
    {
        $errors = [];

        // 1. job_id
        if (empty($data['job_id']) || ! is_string($data['job_id'])) {
            $errors[] = "Field 'job_id' is required and must be a string.";
        }

        // 2. event_metadata
        if (empty($data['event_metadata']) || ! is_array($data['event_metadata'])) {
            $errors[] = "Field 'event_metadata' is required and must be an object.";
        } else {
            if (empty($data['event_metadata']['event_name'])) {
                $errors[] = "Field 'event_metadata.event_name' is required.";
            }
            if (empty($data['event_metadata']['event_date'])) {
                $errors[] = "Field 'event_metadata.event_date' is required.";
            }
        }

        // 3. branding_rules_applied
        if (empty($data['branding_rules_applied']) || ! is_array($data['branding_rules_applied'])) {
            $errors[] = "Field 'branding_rules_applied' is required and must be an object.";
        } else {
            $hexRegex = '/^#[A-Fa-f0-9]{6}$/';
            $primaryColor = $data['branding_rules_applied']['primary_color_hex'] ?? '';
            $secondaryColor = $data['branding_rules_applied']['secondary_color_hex'] ?? '';

            if (! preg_match($hexRegex, $primaryColor)) {
                $errors[] = "Field 'branding_rules_applied.primary_color_hex' must match 6-digit hex format (e.g. #003366).";
            }
            if (! preg_match($hexRegex, $secondaryColor)) {
                $errors[] = "Field 'branding_rules_applied.secondary_color_hex' must match 6-digit hex format (e.g. #FFCC00).";
            }
        }

        // 4. visual_slides_layout
        $validTemplates = ['cover', 'content_list', 'quote', 'cta'];
        if (empty($data['visual_slides_layout']) || ! is_array($data['visual_slides_layout'])) {
            $errors[] = "Field 'visual_slides_layout' is required and must be an array of slides.";
        } else {
            foreach ($data['visual_slides_layout'] as $index => $slide) {
                if (! isset($slide['slide_number']) || ! is_numeric($slide['slide_number'])) {
                    $errors[] = "Slide at index {$index} must have a valid 'slide_number'.";
                }
                if (empty($slide['layout_template']) || ! in_array($slide['layout_template'], $validTemplates, true)) {
                    $errors[] = "Slide at index {$index} 'layout_template' must be one of: ".implode(', ', $validTemplates);
                }
                if (! isset($slide['headline']) || strlen($slide['headline']) > 50) {
                    $errors[] = "Slide at index {$index} 'headline' is required and must be max 50 characters.";
                }
                if (isset($slide['body_text']) && ! is_array($slide['body_text'])) {
                    $errors[] = "Slide at index {$index} 'body_text' must be an array of strings.";
                }
            }
        }

        // 5. social_media_caption
        if (empty($data['social_media_caption']) || ! is_array($data['social_media_caption'])) {
            $errors[] = "Field 'social_media_caption' is required and must be an object.";
        } else {
            $caption = $data['social_media_caption'];
            if (empty($caption['hook_sentence'])) {
                $errors[] = "Field 'social_media_caption.hook_sentence' is required.";
            }
            if (empty($caption['body_paragraph'])) {
                $errors[] = "Field 'social_media_caption.body_paragraph' is required.";
            }
            if (empty($caption['call_to_action'])) {
                $errors[] = "Field 'social_media_caption.call_to_action' is required.";
            }
            if (! isset($caption['hashtags']) || ! is_array($caption['hashtags'])) {
                $errors[] = "Field 'social_media_caption.hashtags' is required and must be an array.";
            }
        }

        // 6. workflow_status
        $validStatuses = ['PENDING_REVIEW', 'APPROVED', 'REJECTED', 'INCOMPLETE_DATA'];
        if (empty($data['workflow_status']) || ! in_array($data['workflow_status'], $validStatuses, true)) {
            $errors[] = "Field 'workflow_status' must be one of: ".implode(', ', $validStatuses);
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'data' => $data,
        ];
    }
}
